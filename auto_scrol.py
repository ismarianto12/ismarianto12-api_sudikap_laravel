#!/usr/bin/env python3
import os
import time
import subprocess
import sys
import re

class VysorAutoScroll:
    def __init__(self):
        self.vysor_port = 53516  # Port default Vysor
        self.device_id = None
        self.detect_vysor_device()
    
    def detect_vysor_device(self):
        """Mendeteksi device yang terhubung melalui Vysor"""
        try:
            # Cek device yang terhubung
            result = subprocess.run("adb devices", shell=True, 
                                  capture_output=True, text=True)
            
            lines = result.stdout.strip().split('\n')[1:]  # Skip header
            
            for line in lines:
                if line.strip() and 'device' in line:
                    device_id = line.split('\t')[0]
                    # Cek jika device adalah Vysor (biasanya ada di localhost)
                    if 'localhost' in device_id or '127.0.0.1' in device_id:
                        self.device_id = device_id
                        print(f"Vysor device detected: {device_id}")
                        return True
            
            print("Vysor device tidak terdeteksi. Pastikan Vysor sedang berjalan.")
            return False
            
        except Exception as e:
            print(f"Error detecting Vysor: {e}")
            return False
    
    def run_adb_command(self, command):
        """Menjalankan perintah ADB dengan device specific"""
        try:
            if self.device_id:
                full_command = f"adb -s {self.device_id} {command}"
            else:
                full_command = f"adb {command}"
                
            result = subprocess.run(full_command, shell=True, 
                                  capture_output=True, text=True)
            return result.stdout
        except Exception as e:
            print(f"ADB Error: {e}")
            return None
    
    def get_screen_info(self):
        """Mendapatkan informasi layar device"""
        try:
            # Dapatkan ukuran layar
            size_output = self.run_adb_command("shell wm size")
            if size_output and "Physical size" in size_output:
                size_match = re.search(r'(\d+)x(\d+)', size_output)
                if size_match:
                    width, height = int(size_match.group(1)), int(size_match.group(2))
                    print(f"Screen size: {width}x{height}")
                    return width, height
            
            # Fallback ke ukuran default jika tidak terdeteksi
            return 1080, 1920
            
        except Exception as e:
            print(f"Error getting screen info: {e}")
            return 1080, 1920
    
    def scroll_down(self, duration=300):
        """Scroll down dengan koordinat yang dinamis"""
        width, height = self.get_screen_info()
        
        # Koordinat untuk swipe (dari bawah ke atas)
        start_x = width // 2
        start_y = int(height * 0.8)  # 80% dari bawah
        end_x = width // 2
        end_y = int(height * 0.2)    # 20% dari atas
        
        command = f"shell input swipe {start_x} {start_y} {end_x} {end_y} {duration}"
        self.run_adb_command(command)
        return f"Swiped from ({start_x},{start_y}) to ({end_x},{end_y})"
    
    def scroll_up(self, duration=300):
        """Scroll up dengan koordinat yang dinamis"""
        width, height = self.get_screen_info()
        
        # Koordinat untuk swipe (dari atas ke bawah)
        start_x = width // 2
        start_y = int(height * 0.2)  # 20% dari atas
        end_x = width // 2
        end_y = int(height * 0.8)    # 80% dari bawah
        
        command = f"shell input swipe {start_x} {start_y} {end_x} {end_y} {duration}"
        self.run_adb_command(command)
        return f"Swiped from ({start_x},{start_y}) to ({end_x},{end_y})"
    
    def auto_scroll_continuous(self, direction="down", count=50, delay=0.8, duration=300):
        """Auto scroll terus menerus"""
        if not self.device_id:
            print("Device Vysor tidak terdeteksi!")
            return
        
        print(f"Memulai auto-scroll {direction}...")
        print(f"Jumlah: {count}, Delay: {delay}s, Duration: {duration}ms")
        print("Tekan Ctrl+C untuk menghentikan")
        
        try:
            for i in range(count):
                if direction.lower() == "down":
                    result = self.scroll_down(duration)
                else:
                    result = self.scroll_up(duration)
                
                print(f"Scroll #{i+1}: {result}")
                time.sleep(delay)
                
        except KeyboardInterrupt:
            print("\nAuto-scroll dihentikan oleh user")
    
    def test_connection(self):
        """Test koneksi ke device Vysor"""
        print("Testing Vysor connection...")
        result = self.run_adb_command("shell echo 'Connected successfully'")
        if result and "Connected successfully" in result:
            print("✓ Koneksi Vysor berhasil!")
            return True
        else:
            print("✗ Koneksi Vysor gagal")
            return False

def main():
    print("=== Vysor Auto-Scroll Tool ===")
    
    # Inisialisasi
    vysor_scroll = VysorAutoScroll()
    
    if not vysor_scroll.device_id:
        print("Pastikan:")
        print("1. Vysor sudah terinstall dan berjalan")
        print("2. Device Android terhubung di Vysor")
        print("3. USB debugging diaktifkan pada device")
        return
    
    # Test koneksi
    if not vysor_scroll.test_connection():
        return
    
    # Menu utama
    while True:
        print("\n=== MENU ===")
        print("1. Auto Scroll Down")
        print("2. Auto Scroll Up")
        print("3. Custom Scroll")
        print("4. Test Connection")
        print("5. Exit")
        
        choice = input("Pilih opsi (1-5): ").strip()
        
        if choice == "1":
            count = int(input("Jumlah scroll: ") or "30")
            delay = float(input("Delay (detik): ") or "0.8")
            vysor_scroll.auto_scroll_continuous("down", count, delay)
            
        elif choice == "2":
            count = int(input("Jumlah scroll: ") or "30")
            delay = float(input("Delay (detik): ") or "0.8")
            vysor_scroll.auto_scroll_continuous("up", count, delay)
            
        elif choice == "3":
            direction = input("Direction (down/up): ").strip().lower()
            count = int(input("Jumlah scroll: ") or "20")
            delay = float(input("Delay (detik): ") or "0.5")
            duration = int(input("Swipe duration (ms): ") or "300")
            
            if direction in ["down", "up"]:
                vysor_scroll.auto_scroll_continuous(direction, count, delay, duration)
            else:
                print("Direction harus 'down' atau 'up'")
                
        elif choice == "4":
            vysor_scroll.test_connection()
            
        elif choice == "5":
            print("Terima kasih!")
            break
            
        else:
            print("Pilihan tidak valid!")

if __name__ == "__main__":
    main()
