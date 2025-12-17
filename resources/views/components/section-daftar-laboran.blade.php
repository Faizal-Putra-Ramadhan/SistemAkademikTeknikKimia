<div class="section">
                <div class="section-header">
                    <div class="section-title">Daftar Laboran Laboratorium</div>
                    <input type="text" class="search-box" placeholder="Pencarian...">
                </div>
                <div class="filter-tabs">
                    <button class="filter-tab">Admin</button>
                    <button class="filter-tab">Tendik</button>
                    <button class="filter-tab">Dosen</button>
                    <button class="filter-tab">Mahasiswa</button>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Laboratorium</th>
                            <th>Nama Laboran</th>
                            <th>UserID</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Role User</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{ $slot }}
                    </tbody>
                </table>
            </div>