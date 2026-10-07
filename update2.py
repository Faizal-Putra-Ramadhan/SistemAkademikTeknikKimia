import os, re

template_file = r'd:\belajar web\Laravel\bismilaahhh\Project_Tekkim\template_laboran.blade.php'

with open(template_file, 'r', encoding='utf-8') as f:
    template = f.read()

def write_view(path, role, has_crud):
    if not os.path.exists(path):
        return

    content = template
    
    if has_crud:
        content = content.replace('ROUTE_CREATE', f"{{{{ route('{role}.pengumuman.create') }}}}")
        content = content.replace('ROUTE_EDIT', f"{{{{ route('{role}.pengumuman.edit', $item->id) }}}}")
        content = content.replace('ROUTE_DESTROY', f"{{{{ route('{role}.pengumuman.destroy', $item->id) }}}}")
    else:
        # Remove create button from header
        content = re.sub(r'<a href="ROUTE_CREATE"[^>]*>\s*\+\s*Buat Pengumuman\s*</a>', '', content, flags=re.DOTALL)
        
        # Remove edit/delete block
        content = re.sub(r'@if\(\$item->author === \$user->Nama\).*?@endif', '', content, flags=re.DOTALL)
        
        # Remove empty state create button
        content = re.sub(r'<a href="ROUTE_CREATE"[^>]*>Buat Pengumuman Pertama</a>', '', content, flags=re.DOTALL)

    with open(path, 'w', encoding='utf-8') as f:
        f.write(content)
    print('Updated ' + path)

base = r'd:\belajar web\Laravel\bismilaahhh\Project_Tekkim\resources\views'

write_view(os.path.join(base, 'kepala-lab', 'pengumuman', 'index.blade.php'), 'kepala-lab', True)
write_view(os.path.join(base, 'laboran', 'kelola-pengumuman.blade.php'), 'laboran', True)
write_view(os.path.join(base, 'kaprodi', 'pengumuman', 'index.blade.php'), 'kaprodi', True)
write_view(os.path.join(base, 'pengumuman', 'index.blade.php'), 'pengumuman', True)
write_view(os.path.join(base, 'safety-officer', 'pengumuman', 'index.blade.php'), 'safety-officer', True)

write_view(os.path.join(base, 'dosen', 'pengumuman', 'index.blade.php'), 'dosen', False)
write_view(os.path.join(base, 'mahasiswa', 'pengumuman.blade.php'), 'mahasiswa', False)
write_view(os.path.join(base, 'peneliti-eksternal', 'pengumuman.blade.php'), 'peneliti-eksternal', False)

