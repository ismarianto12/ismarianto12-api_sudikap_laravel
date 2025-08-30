import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
import yfinance as yf
from datetime import datetime, timedelta
import warnings
warnings.filterwarnings('ignore')

# Coba import TA-Lib, jika error gunakan implementasi manual
try:
    import talib
    TALIB_AVAILABLE = True
    print("TA-Lib berhasil diimport")
except ImportError:
    TALIB_AVAILABLE = False
    print("TA-Lib tidak tersedia, menggunakan implementasi manual")
    
    # Implementasi manual untuk indikator dasar
    def manual_SMA(data, period):
        return data.rolling(window=period).mean()
    
    def manual_RSI(data, period=14):
        delta = data.diff()
        gain = (delta.where(delta > 0, 0)).rolling(window=period).mean()
        loss = (-delta.where(delta < 0, 0)).rolling(window=period).mean()
        rs = gain / loss
        return 100 - (100 / (1 + rs))
    
    def manual_MACD(data, fast=12, slow=26, signal=9):
        ema_fast = data.ewm(span=fast).mean()
        ema_slow = data.ewm(span=slow).mean()
        macd = ema_fast - ema_slow
        signal_line = macd.ewm(span=signal).mean()
        hist = macd - signal_line
        return macd, signal_line, hist

class XAUUSDAnalyzer:
    def __init__(self):
        self.data = None
        self.current_price = 0
        self.signals = []
        self.data_loaded = False
        
    def fetch_data(self):
        """Mengambil data XAU/USD dari Yahoo Finance"""
        try:
            print("Mengambil data XAU/USD dari Yahoo Finance...")
            # GC=F adalah simbol untuk gold futures di Yahoo Finance
            data = yf.download('GC=F', period='5d', interval='15m')
            
            if len(data) == 0:
                # Fallback ke data historis jika data real-time tidak tersedia
                print("Data real-time tidak tersedia, menggunakan data historis...")
                data = yf.download('GC=F', period='10d', interval='1h')
            
            if len(data) == 0:
                print("Gagal mengambil data. Silakan cek koneksi internet.")
                self.data_loaded = False
                return False
            
            self.data = data
            self.current_price = data['Close'].iloc[-1]
            self.data_loaded = True
            print(f"Data berhasil diambil. {len(data)} candlestick tersedia.")
            print(f"Price terakhir: ${self.current_price:.2f}")
            return True
            
        except Exception as e:
            print(f"Error fetching data: {e}")
            self.data_loaded = False
            return False
    
    def calculate_indicators(self):
        """Menghitung semua indikator teknikal"""
        if not self.data_loaded or self.data is None:
            print("Error: Data belum dimuat. Silakan jalankan fetch_data() terlebih dahulu.")
            return None
        
        try:
            df = self.data.copy()
            
            # Konversi ke numpy array untuk TA-Lib
            close_prices = np.array(df['Close'].values, dtype=float)
            high_prices = np.array(df['High'].values, dtype=float)
            low_prices = np.array(df['Low'].values, dtype=float)
            
            if TALIB_AVAILABLE:
                try:
                    # Moving Averages dengan TA-Lib
                    df['MA20'] = talib.SMA(close_prices, timeperiod=20)
                    df['MA50'] = talib.SMA(close_prices, timeperiod=50)
                    df['RSI'] = talib.RSI(close_prices, timeperiod=14)
                    
                    # MACD
                    macd, macd_signal, macd_hist = talib.MACD(
                        close_prices, fastperiod=12, slowperiod=26, signalperiod=9
                    )
                    df['MACD'] = macd
                    df['MACD_signal'] = macd_signal
                    df['MACD_hist'] = macd_hist
                    
                except Exception as e:
                    print(f"Error menggunakan TA-Lib: {e}")
                    print("Beralih ke perhitungan manual...")
                    # Set flag untuk menggunakan perhitungan manual
                    use_manual = True
            else:
                use_manual = True
            
            # Jika TA-Lib tidak tersedia atau error, gunakan perhitungan manual
            if not TALIB_AVAILABLE or 'use_manual' in locals():
                # Moving Averages manual
                df['MA20'] = manual_SMA(df['Close'], 20)
                df['MA50'] = manual_SMA(df['Close'], 50)
                df['RSI'] = manual_RSI(df['Close'], 14)
                df['MACD'], df['MACD_signal'], df['MACD_hist'] = manual_MACD(df['Close'])
            
            # Bollinger Bands manual
            df['BB_middle'] = df['Close'].rolling(window=20).mean()
            bb_std = df['Close'].rolling(window=20).std()
            df['BB_upper'] = df['BB_middle'] + (bb_std * 2)
            df['BB_lower'] = df['BB_middle'] - (bb_std * 2)
            
            # Stochastic Oscillator manual
            low_14 = df['Low'].rolling(window=14).min()
            high_14 = df['High'].rolling(window=14).max()
            df['STOCH_K'] = 100 * ((df['Close'] - low_14) / (high_14 - low_14))
            df['STOCH_D'] = df['STOCH_K'].rolling(window=3).mean()
            
            # ATR manual
            high_low = df['High'] - df['Low']
            high_close = np.abs(df['High'] - df['Close'].shift())
            low_close = np.abs(df['Low'] - df['Close'].shift())
            ranges = pd.concat([high_low, high_close, low_close], axis=1)
            true_range = np.max(ranges, axis=1)
            df['ATR'] = true_range.rolling(window=14).mean()
            
            # Handle NaN values
            df = df.fillna(method='bfill').fillna(method='ffill')
            
            # Simpan data yang sudah diproses
            self.data = df
            print("Indikator teknikal berhasil dihitung.")
            return df
            
        except Exception as e:
            print(f"Error dalam calculate_indicators: {e}")
            return None
    
    def generate_signals(self):
        """Generate sinyal trading berdasarkan indikator"""
        if not self.data_loaded or self.data is None:
            print("Error: Data belum dimuat atau diolah.")
            return []
        
        try:
            df = self.data
            # Pastikan ada cukup data
            if len(df) < 2:
                print("Data tidak cukup untuk menghasilkan sinyal")
                return []
                
            current = df.iloc[-1]
            prev = df.iloc[-2] if len(df) > 1 else current
            
            signals = []
            
            # 1. RSI Analysis
            rsi_value = current['RSI']
            if not np.isnan(rsi_value):
                if rsi_value > 70:
                    signals.append(("RSI", "OVERBOUGHT", "Bearish", rsi_value))
                elif rsi_value < 30:
                    signals.append(("RSI", "OVERSOLD", "Bullish", rsi_value))
                else:
                    signals.append(("RSI", "NEUTRAL", "Neutral", rsi_value))
            
            # 2. MACD Analysis
            if not np.isnan(current['MACD']) and not np.isnan(current['MACD_signal']):
                if current['MACD'] > current['MACD_signal']:
                    signals.append(("MACD", "BULLISH", "Bullish", current['MACD']))
                else:
                    signals.append(("MACD", "BEARISH", "Bearish", current['MACD']))
            
            # 3. Moving Average Analysis
            if not np.isnan(current['MA20']):
                if current['Close'] > current['MA20']:
                    signals.append(("MA20", "ABOVE_MA20", "Bullish", current['MA20']))
                else:
                    signals.append(("MA20", "BELOW_MA20", "Bearish", current['MA20']))
            
            if not np.isnan(current['MA20']) and not np.isnan(current['MA50']):
                if current['MA20'] > current['MA50']:
                    signals.append(("MA_CROSS", "MA20_ABOVE_MA50", "Bullish", current['MA50']))
                else:
                    signals.append(("MA_CROSS", "MA20_BELOW_MA50", "Bearish", current['MA50']))
            
            # 4. Bollinger Bands Analysis
            if not np.isnan(current['BB_upper']) and not np.isnan(current['BB_lower']):
                bb_range = current['BB_upper'] - current['BB_lower']
                if bb_range > 0:
                    bb_position = (current['Close'] - current['BB_lower']) / bb_range
                    if bb_position > 0.8:
                        signals.append(("BB", "NEAR_UPPER", "Bearish", bb_position))
                    elif bb_position < 0.2:
                        signals.append(("BB", "NEAR_LOWER", "Bullish", bb_position))
                    else:
                        signals.append(("BB", "MIDDLE", "Neutral", bb_position))
            
            # 5. Stochastic Analysis
            if not np.isnan(current['STOCH_K']):
                if current['STOCH_K'] > 80:
                    signals.append(("STOCH", "OVERBOUGHT", "Bearish", current['STOCH_K']))
                elif current['STOCH_K'] < 20:
                    signals.append(("STOCH", "OVERSOLD", "Bullish", current['STOCH_K']))
                else:
                    signals.append(("STOCH", "NEUTRAL", "Neutral", current['STOCH_K']))
            
            self.signals = signals
            print(f"Berhasil menghasilkan {len(signals)} sinyal trading.")
            return signals
            
        except Exception as e:
            print(f"Error dalam generate_signals: {e}")
            return []
    
    def calculate_support_resistance(self):
        """Menghitung level support dan resistance"""
        if not self.data_loaded or self.data is None:
            return {
                'pivot': 0, 'support1': 0, 'resistance1': 0,
                'recent_high': 0, 'recent_low': 0
            }
        
        try:
            df = self.data
            
            # Ambil data yang valid (tanpa NaN)
            valid_data = df.dropna()
            if len(valid_data) < 5:
                valid_data = df.tail(5)
            
            recent_high = valid_data['High'].max() if len(valid_data) > 0 else self.current_price
            recent_low = valid_data['Low'].min() if len(valid_data) > 0 else self.current_price
            recent_close = valid_data['Close'].iloc[-1] if len(valid_data) > 0 else self.current_price
            
            # Pivot Points calculation
            pivot = (recent_high + recent_low + recent_close) / 3
            r1 = (2 * pivot) - recent_low
            s1 = (2 * pivot) - recent_high
            
            return {
                'pivot': round(pivot, 2),
                'support1': round(s1, 2),
                'resistance1': round(r1, 2),
                'recent_high': round(recent_high, 2),
                'recent_low': round(recent_low, 2)
            }
            
        except Exception as e:
            print(f"Error dalam calculate_support_resistance: {e}")
            return {
                'pivot': round(self.current_price, 2),
                'support1': round(self.current_price * 0.99, 2),
                'resistance1': round(self.current_price * 1.01, 2),
                'recent_high': round(self.current_price, 2),
                'recent_low': round(self.current_price, 2)
            }
    
    def analyze_market_condition(self):
        """Menganalisis kondisi market secara keseluruhan"""
        if not self.signals:
            return "NEUTRAL", 50
        
        try:
            bull_count = sum(1 for signal in self.signals if signal[2] == "Bullish")
            bear_count = sum(1 for signal in self.signals if signal[2] == "Bearish")
            
            total_signals = len(self.signals)
            if total_signals == 0:
                return "NEUTRAL", 50
                
            bull_percentage = (bull_count / total_signals) * 100
            
            if bull_percentage > 60:
                return "STRONGLY_BULLISH", bull_percentage
            elif bull_percentage > 40:
                return "BULLISH", bull_percentage
            elif bull_count < bear_count:
                return "BEARISH", bull_percentage
            else:
                return "NEUTRAL", bull_percentage
                
        except Exception as e:
            print(f"Error dalam analyze_market_condition: {e}")
            return "NEUTRAL", 50
    
    def calculate_position_size(self, account_balance=10000, risk_percent=1):
        """Menghitung ukuran posisi berdasarkan risk management"""
        try:
            current_price = self.current_price
            
            # Gunakan ATR jika tersedia, otherwise gunakan persentase fixed
            if hasattr(self, 'data') and self.data is not None and 'ATR' in self.data.columns:
                atr = self.data['ATR'].iloc[-1] if not np.isnan(self.data['ATR'].iloc[-1]) else current_price * 0.01
                stop_loss_distance = max(atr * 1.5, current_price * 0.005)
            else:
                stop_loss_distance = current_price * 0.01  # 1% default
            
            risk_amount = account_balance * (risk_percent / 100)
            position_size = risk_amount / stop_loss_distance
            
            return {
                'position_size': round(position_size, 2),
                'stop_loss_distance': round(stop_loss_distance, 2),
                'risk_amount': round(risk_amount, 2),
                'stop_loss_long': round(current_price - stop_loss_distance, 2),
                'stop_loss_short': round(current_price + stop_loss_distance, 2)
            }
            
        except Exception as e:
            print(f"Error dalam calculate_position_size: {e}")
            return {
                'position_size': 0,
                'stop_loss_distance': 0,
                'risk_amount': 0,
                'stop_loss_long': 0,
                'stop_loss_short': 0
            }
    
    def generate_trading_recommendation(self):
        """Generate rekomendasi trading"""
        try:
            market_condition, strength = self.analyze_market_condition()
            sr_levels = self.calculate_support_resistance()
            risk_management = self.calculate_position_size()
            
            recommendation = {
                'market_condition': market_condition,
                'condition_strength': round(strength, 1),
                'current_price': round(self.current_price, 2),
                'recommendation': '',
                'entry_suggestions': [],
                'risk_management': risk_management,
                'support_resistance': sr_levels
            }
            
            if "BULLISH" in market_condition:
                recommendation['recommendation'] = "CONSIDER LONG POSITION"
                recommendation['entry_suggestions'] = [
                    f"Buy near: ${sr_levels['support1']:.2f}",
                    f"Stop loss: ${risk_management['stop_loss_long']:.2f}",
                    f"Take profit 1: ${sr_levels['resistance1']:.2f}",
                    f"Take profit 2: ${sr_levels['recent_high']:.2f}"
                ]
            elif "BEARISH" in market_condition:
                recommendation['recommendation'] = "CONSIDER SHORT POSITION"
                recommendation['entry_suggestions'] = [
                    f"Sell near: ${sr_levels['resistance1']:.2f}",
                    f"Stop loss: ${risk_management['stop_loss_short']:.2f}",
                    f"Take profit 1: ${sr_levels['support1']:.2f}",
                    f"Take profit 2: ${sr_levels['recent_low']:.2f}"
                ]
            else:
                recommendation['recommendation'] = "WAIT FOR CLEARER SIGNAL"
                recommendation['entry_suggestions'] = [
                    "Market dalam kondisi neutral",
                    "Tunggu breakout support/resistance",
                    "Perhatikan volume dan momentum"
                ]
            
            return recommendation
            
        except Exception as e:
            print(f"Error dalam generate_trading_recommendation: {e}")
            return {
                'market_condition': "ERROR",
                'condition_strength': 0,
                'current_price': round(self.current_price, 2),
                'recommendation': "ERROR - Silakan coba lagi",
                'entry_suggestions': ["Terjadi error dalam analisis"],
                'risk_management': {},
                'support_resistance': {}
            }
    
    def plot_analysis(self):
        """Plot analisis teknikal"""
        if not self.data_loaded or self.data is None:
            print("Tidak ada data untuk di-plot")
            return
        
        try:
            df = self.data.tail(50)
            
            # Hapus baris dengan NaN values untuk plotting
            plot_data = df.dropna()
            
            if len(plot_data) < 10:
                print("Data tidak cukup untuk plotting")
                return
            
            fig, axes = plt.subplots(3, 1, figsize=(15, 10))
            
            # Plot 1: Price dengan MA dan Bollinger Bands
            axes[0].plot(plot_data.index, plot_data['Close'], label='Price', linewidth=2, color='blue')
            axes[0].plot(plot_data.index, plot_data['MA20'], label='MA20', alpha=0.7, color='orange')
            axes[0].plot(plot_data.index, plot_data['MA50'], label='MA50', alpha=0.7, color='red')
            axes[0].plot(plot_data.index, plot_data['BB_upper'], label='BB Upper', linestyle='--', alpha=0.7, color='gray')
            axes[0].plot(plot_data.index, plot_data['BB_lower'], label='BB Lower', linestyle='--', alpha=0.7, color='gray')
            axes[0].fill_between(plot_data.index, plot_data['BB_upper'], plot_data['BB_lower'], alpha=0.1, color='gray')
            axes[0].set_title('XAU/USD Price Analysis')
            axes[0].legend()
            axes[0].grid(True, alpha=0.3)
            
            # Plot 2: RSI
            axes[1].plot(plot_data.index, plot_data['RSI'], label='RSI', color='purple', linewidth=2)
            axes[1].axhline(70, linestyle='--', color='red', alpha=0.7, label='Overbought (70)')
            axes[1].axhline(30, linestyle='--', color='green', alpha=0.7, label='Oversold (30)')
            axes[1].axhline(50, linestyle='--', color='gray', alpha=0.5, label='Neutral (50)')
            axes[1].set_title('RSI Indicator')
            axes[1].legend()
            axes[1].grid(True, alpha=0.3)
            axes[1].set_ylim(0, 100)
            
            # Plot 3: MACD
            axes[2].plot(plot_data.index, plot_data['MACD'], label='MACD', color='blue', linewidth=2)
            axes[2].plot(plot_data.index, plot_data['MACD_signal'], label='Signal', color='red', linewidth=2)
            axes[2].axhline(0, linestyle='--', color='black', alpha=0.5)
            axes[2].set_title('MACD Indicator')
            axes[2].legend()
            axes[2].grid(True, alpha=0.3)
            
            plt.tight_layout()
            plt.show()
            
        except Exception as e:
            print(f"Error dalam plot_analysis: {e}")
    
    def print_analysis_report(self):
        """Print laporan analisis lengkap"""
        print("=" * 60)
        print("ANALISIS XAU/USD - SHORT TERM TRADING (1 HARI)")
        print("=" * 60)
        print(f"Waktu Analisis: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}")
        print(f"Price Terakhir: ${self.current_price:.2f}")
        print()
        
        # Support dan Resistance
        sr = self.calculate_support_resistance()
        print("SUPPORT & RESISTANCE:")
        print(f"Resistance 1: ${sr['resistance1']:.2f}")
        print(f"Pivot Point: ${sr['pivot']:.2f}")
        print(f"Support 1: ${sr['support1']:.2f}")
        print(f"Recent High: ${sr['recent_high']:.2f}")
        print(f"Recent Low: ${sr['recent_low']:.2f}")
        print()
        
        # Sinyal Indikator
        print("SINYAL INDIKATOR:")
        if self.signals:
            for signal in self.signals:
                indicator, condition, bias, value = signal
                print(f"{indicator:8} | {condition:15} | {bias:8} | {value:8.2f}")
        else:
            print("Tidak ada sinyal yang dihasilkan")
        print()
        
        # Rekomendasi Trading
        recommendation = self.generate_trading_recommendation()
        print("REKOMENDASI TRADING:")
        print(f"Kondisi Market: {recommendation['market_condition']}")
        print(f"Strength: {recommendation['condition_strength']:.1f}%")
        print(f"Rekomendasi: {recommendation['recommendation']}")
        print()
        
        print("ENTRY SUGGESTIONS:")
        for suggestion in recommendation['entry_suggestions']:
            print(f"• {suggestion}")
        print()
        
        # Risk Management
        rm = recommendation['risk_management']
        print("RISK MANAGEMENT:")
        print(f"Position Size: {rm['position_size']:.2f} units (untuk account $10,000)")
        print(f"Stop Loss Distance: ${rm['stop_loss_distance']:.2f}")
        print(f"Risk Amount: ${rm['risk_amount']:.2f} (1% dari account)")
        print()
        
        print("CATATAN PENTING:")
        print("• Gunakan Stop Loss wajib untuk trading short-term")
        print("• Monitor news ekonomi yang mempengaruhi USD dan emas")
        print("• Volume trading tinggi pada sesi London (08:00-17:00 GMT) dan New York (13:00-22:00 GMT)")
        print("• Risk management adalah kunci utama")
        print("=" * 60)

# Main execution
def main():
    print("Memulai analisis XAU/USD...")
    
    # Initialize analyzer
    analyzer = XAUUSDAnalyzer()
    
    # Fetch data
    if not analyzer.fetch_data():
        print("Gagal mengambil data. Pastikan koneksi internet tersedia.")
        return
    
    # Calculate indicators
    if analyzer.calculate_indicators() is None:
        print("Gagal menghitung indikator. Analisis dihentikan.")
        return
    
    # Generate signals
    analyzer.generate_signals()
    
    # Print analysis report
    analyzer.print_analysis_report()
    
    # Show plots
    try:
        analyzer.plot_analysis()
    except Exception as e:
        print(f"Error saat plotting: {e}")
        print("Melanjutkan tanpa plot...")
    
    print("Analisis selesai. Selalu lakukan penelitian sendiri sebelum trading!")

if __name__ == "__main__":
    main()