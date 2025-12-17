<div class="section">
                <div class="section-header">
                    <div class="section-title">Daftar Laboratorium dan Departemen</div>
                    <input type="text" class="search-box" placeholder="Pencarian...">
                </div>
                <div class="filter-tabs">
                    <button class="filter-tab">Lab Aktif</button>
                    <button class="filter-tab">Lab Tidak Aktif</button>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Laboratorium</th>
                            <th>Kepala Laboratorium</th>
                            <th>Admin Laboratorium</th>
                            <th>Safety Officer</th>
                            <th>e-mail Laboratorium</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{ $slot }}
                    </tbody>
                </table>
            </div>