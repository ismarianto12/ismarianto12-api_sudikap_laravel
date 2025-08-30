#!/usr/bin/env python3
import os
import time
import subprocess
import random
import re

class TikTokAutoScroll:
    def __init__(self):
        self.device_id = None
        self.screen_width = 1080
        self.screen_height = 1920
        self.detect_device()
        self.get_screen_size()
    
    def detect_device(self):
        """Mendeteksi device yang terhubung"""
        try:
            result = subprocess.run("adb devices", shell=True, 
                                  capture_output=True, text=True)
            
            lines = result.stdout.strip().split('\n')[1:]
            for line in lines:
                if line.strip() and 'device' in line:
                    self.device_id = line.split('\t')[0]
                    print(f"Device detected: {self.device_id}")
                    return True
            
            print("Tidak ada device yang terdeteksi!")
            return False
            
        except Exception as e:
            print(f"Error detecting device: {e}")
            return False
    
    def get_screen_size(self):
        """Mendapatkan ukuran layar device"""
        try:
            result = self.run_adb_command("shell wm size")
            if result and "Physical size" in result:
                size_match = re.search(r'(\d+)x(\d+)', result)
                if size_match:
                    self.screen_width = int(size_match.group(1))
                    self.screen_height = int(size_match.group(2))
                    print(f"Screen size: {self.screen_width}x{self.screen_height}")
        except:
            print("Menggunakan ukuran layar default 1080x1920")
    
    def run_adb_command(self, command):
        """Menjalankan perintah ADB"""
        try:
            if self.device_id:
                full_cmd = f"adb -s {self.device_id} {command}"
            else:
                full_cmd = f"adb {command}"
                
            result = subprocess.run(full_cmd, shell=True, 
                                  capture_output=True, text=True)
            return result.stdout
        except Exception as e:
            print(f"ADB Error: {e}")
            return None
    
    def is_tiktok_foreground(self):
        """Memeriksa apakah TikTok sedang di foreground"""
        try:
            result = self.run_adb_command("shell dumpsys window windows | grep -E 'mCurrentFocus|mFocusedApp'")
            if result and ('tiktok' in result.lower() or 'com.zhiliaoapp.musically' in result):
                return True
            return False
        except:
            return False
    
    def tiktok_scroll_down(self):
        """Scroll down khusus untuk TikTok"""
        # Koordinat untuk swipe di TikTok (lebih natural)
        start_x = self.screen_width // 2
        start_y = self.screen_height * 0.7  # 70% dari bawah
        end_x = self.screen_width // 2
        end_y = self.screen_height * 0.3    # 30% dari atas
        
        # Durasi swipe yang natural untuk TikTok
        duration = random.randint(250, 400)
        
        command = f"shell input swipe {start_x} {start_y} {end_x} {end_y} {duration}"
        self.run_adb_command(command)
        
        return duration
    
    def tiktok_scroll_up(self):
        """Scroll up khusus untuk TikTok"""
        # Koordinat untuk swipe ke atas
        start_x = self.screen_width // 2
        start_y = self.screen_height * 0.3  # 30% dari atas
        end_x = self.screen_width // 2
        end_y = self.screen_height * 0.7    # 70% dari bawah
        
        duration = random.randint(250, 400)
        
        command = f"shell input swipe {start_x} {start_y} {end_x} {end_y} {duration}"
        self.run_adb_command(command)
        
        return duration
    
    def like_video(self):
        """Memberikan like pada video (double tap)"""
        try:
            # Double tap di tengah layar
            tap_x = self.screen_width // 2
            tap_y = self.screen_height // 2
            
            self.run_adb_command(f"shell input tap {tap_x} {tap_y}")
            time.sleep(0.1)
            self.run_adb_command(f"shell input tap {tap_x} {tap_y}")
            print("✓ Memberikan like")
        except:
            print("✗ Gagal memberikan like")
    
    def auto_scroll_tiktok(self, scroll_count=100, min_delay=3.0, max_delay=6.0, auto_like=False, like_chance=30):
        """
        Auto scroll TikTok dengan behavior yang natural
        
        Parameters:
        - scroll_count: jumlah scroll
        - min_delay: delay minimum antara scroll (detik)
        - max_delay: delay maksimum antara scroll (detik)
        - auto_like: apakah memberikan like otomatis
        - like_chance: persentase chance untuk like (1-100)
        """
        
        if not self.is_tiktok_foreground():
            print("⚠️  TikTok tidak berada di foreground!")
            print("Buka aplikasi TikTok terlebih dahulu dan pastikan video sedang diputar")
            response = input("Lanjutkan anyway? (y/n): ").lower()
            if response != 'y':
                return
        
        print("🎵 Memulai Auto-Scroll TikTok...")
        print(f"🔢 Jumlah scroll: {scroll_count}")
        print(f"⏱️  Delay: {min_delay}-{max_delay} detik")
        print(f"❤️  Auto like: {'Aktif' if auto_like else 'Non-aktif'}")
        print("⏸️  Tekan Ctrl+C untuk berhenti")
        print("-" * 50)
        
        try:
            for i in range(scroll_count):
                if not self.is_tiktok_foreground():
                    print("⚠️  TikTok tidak di foreground, pause...")
                    time.sleep(2)
                    continue
                
                # Scroll down
                duration = self.tiktok_scroll_down()
                
                # Random delay seperti manusia
                delay = random.uniform(min_delay, max_delay)
                
                # Auto like dengan chance tertentu
                if auto_like and random.randint(1, 100) <= like_chance:
                    self.like_video()
                
                print(f"📱 Scroll #{i+1}: {duration}ms, delay: {delay:.1f}s")
                
                # Progress indicator
                if (i + 1) % 10 == 0:
                    print(f"📊 Progress: {i+1}/{scroll_count}")
                
                time.sleep(delay)
                
        except KeyboardInterrupt:
            print("\n⏹️  Auto-scroll dihentikan")
        
        print("✅ Selesai!")

def main():
    print("=== TikTok Auto-Scroller ===")
    print("Pastikan:")
    print("1. TikTok sedang terbuka")
    print("2. Video sedang diputar")
    print("3. Device terhubung via Vysor/USB")
    print()
    
    tiktok = TikTokAutoScroll()
    
    if not tiktok.device_id:
        print("❌ Device tidak terdeteksi!")
        return
    
    # Konfigurasi
    SCROLL_COUNT = 50       # Jumlah video yang akan di-scroll
    MIN_DELAY = 3.0         # Delay minimum (detik)
    MAX_DELAY = 7.0         # Delay maksimum (detik)
    AUTO_LIKE = True        # Auto like video
    LIKE_CHANCE = 25        # 25% chance untuk like
    
    print("Konfigurasi saat ini:")
    print(f"Scroll count: {SCROLL_COUNT}")
    print(f"Delay: {MIN_DELAY}-{MAX_DELAY} detik")
    print(f"Auto like: {AUTO_LIKE} ({LIKE_CHANCE}% chance)")
    print()
    
    input("Tekan Enter untuk mulai auto-scroll...")
    
    tiktok.auto_scroll_tiktok(
        scroll_count=SCROLL_COUNT,
        min_delay=MIN_DELAY,
        max_delay=MAX_DELAY,
        auto_like=AUTO_LIKE,
        like_chance=LIKE_CHANCE
    )

if __name__ == "__main__":
    main()
