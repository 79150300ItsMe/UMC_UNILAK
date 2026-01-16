import os
import re

# Mapping file lama ke file baru
mapping = {
    # Admin folder
    'dashboard.php': 'dasbor.php',
    'patients.php': 'daftar_pasien.php',
    'patient_add.php': 'tambah_pasien.php',
    'patient_edit.php': 'ubah_pasien.php',
    # Dokter folder  
    'consultations.php': 'daftar_konsultasi.php',
    'consultation_add.php': 'tambah_konsultasi.php',
    # Kasir folder
    'payments.php': 'daftar_pembayaran.php',
    'payment_process.php': 'proses_pembayaran.php',
    # Reports
    'transaction_report.php': 'laporan_transaksi.php'
}

def update_file_links(file_path):
    """Update all internal links in a file"""
    try:
        with open(file_path, 'r', encoding='utf-8') as f:
            content = f.read()
        
        original = content
        
        # Replace semua referensi file lama dengan yang baru
        for old, new in mapping.items():
            # Pattern untuk href, header Location, dan include/require
            patterns = [
                (rf'href="([^"]*){re.escape(old)}"', rf'href="\1{new}"'),
                (rf"href='([^']*){re.escape(old)}'", rf"href='\1{new}'"),
                (rf"Location: ([^'\"]*){re.escape(old)}", rf"Location: \1{new}"),
            ]
            
            for pattern, replacement in patterns:
                content = re.sub(pattern, replacement, content)
        
        # Tulis kembali jika ada perubahan
        if content != original:
            with open(file_path, 'w', encoding='utf-8') as f:
                f.write(content)
            print(f"OK Updated: {file_path}")
            return True
        return False
                
    except Exception as e:
        print(f"ERROR updating {file_path}: {e}")
        return False

def process_directory(directory):
    """Process all PHP files in directory"""
    updated_count = 0
    
    for root, dirs, files in os.walk(directory):
        for file in files:
            if file.endswith('.php'):
                file_path = os.path.join(root, file)
                if update_file_links(file_path):
                    updated_count += 1
    
    return updated_count

if __name__ == '__main__':
    base_dir = r'c:\xampp\htdocs\UMC_UNILAK'
    
    print("="*50)
    print("Mengupdate referensi file...")
    print("="*50)
    
    count = process_directory(base_dir)
    
    print("="*50)
    print(f"Selesai! {count} file diupdate")
    print("="*50)
