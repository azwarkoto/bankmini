<?php
include 'koneksi.php';
if(!$_SESSION['login'] == "true"){
    header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <title>App Bank Mini</title>
</head>
<body>
    <div id="navbar">
        <a href="#home" onclick="contentIndex()">Home</a>
        <a href="#akun" onclick="contentAkun()">Akun</a>
        <a href="#transaksi" onclick="contentTransaksi()">Transaksi</a>
        <span class="dropdown">
            <a href='#'>Informasi</a>
            <ul class="dropdown-content">
                <a href="#masterakun" onclick="contentMasterAkun()">Master Akun</a>
                <a href="#masteruser" onclick="contentMasterUser()">Master User</a>
                <a href="#laporan" onclick="contentLaporan()">Laporan</a>
                <a href="#change" onclick="contentChange()">Ganti Password</a>
            </ul>
        </span>
        <a href="#history" onclick="contentHistory()">History</a>
        <a href="logout.php" >Logout</a>
    </div>
    <div id="content">
        
    <script>


    contentIndex();
    function contentIndex(){
        document.getElementById('content').innerHTML= `
                <h1>Bank Mini</h1>
        <p>Selamat datang, <?= strtoupper($_SESSION['nama']) ?></p>
        <p>untuk mengganti menu klik tombol diatas</p>
                `;
    }
    function contentAkun(){
        document.getElementById('content').innerHTML = `
        <h1>Akun</h1>
        <p>Lengkapi data untuk membuat data baru. note: bank mini tidak ada bunga</p>
        <form action="akun.php" method="post">
            <table>
                <tr>
                    <td><label>No Rekening</label></td>
                    <td><input type="number" name="noreg" id="noreg" value="${Math.floor(Math.random() * 10000000000)}" readonly></td>
                </tr>
                <tr>
                    <td><label>Nama</label></td>
                    <td><input type="text" name="nama" id="nama"></td>
                </tr>
                <tr>
                    <td><label>Alamat</label></td>
                    <td><textarea name="alamat" id="alamat" cols="80" rows="10"></textarea></td>
                </tr>
                <tr>
                    <td><input type="submit" value="Simpan" name="akun" class="button"></td>
                </tr>
            </table>
        </form>
        `;
    }
    
    function contentTransaksi(){
        document.getElementById('content').innerHTML=`
        <h1>Transaksi</h1>
        <form action="transaksi.php" method="post">
            <table>
                <tr>
                    <td><label>No Rekening</label></td>
                    <td>
                        <select name="noreg" id="noreg" onchange="tampilDetail()">
                            <?php
                            $sql = "select * from akun order by nama ASC";
                            $query = mysqli_query($koneksi,$sql);
                            while($row = mysqli_fetch_array($query)){
                            ?>
                            <option value='<?= $row["id_akun"] ?>'><?php echo "$row[nama] - $row[id_akun]"; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td><label>Kredit/Debit</label></td>
                    <td>
                        <select name="opsi" id="opsi" onchange="nominal()">
                            <option>...</option>
                            <option value="kredit">Kredit</option>
                            <option value="debit">Debit</option>
                        </select>
                    </td>
                </tr>
                <tr id="nominal">
                    
                </tr>
                <tr id="konfir">
                    
                </tr>
            </table>
        </form>
        `;
    }
    function nominal(){
        document.getElementById('nominal').innerHTML=`
        <td><label>Nominal</label></td>
                <td><input type="number" name="nominal" onkeypress="konfir()"></td>
        `;
    }
    function konfir(){
        document.getElementById('konfir').innerHTML=`
        <td><input type="submit" value="Simpan" name="transaksi" class="button"></td>
        `;
    }

    function contentLaporan(){
        document.getElementById('content').innerHTML=`
        <h1>Laporan</h1>
        <table border=1>
            <tr>
                <th>No</th>
                <th>No Rekening</th>
                <th>Nama</th>
                <th>Saldo</th>
            </tr>
            <?php
            $sql = "select transaksi.id_akun,akun.nama,sum(transaksi.transaksi) from akun right join transaksi on transaksi.id_akun = akun.id_akun GROUP by transaksi.id_akun";
            $query = mysqli_query($koneksi,$sql);
            $no = 1;
            while($row= mysqli_fetch_array($query)){
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td width="20%"><?= $row[0] ?></td>
                <td width="20%"><?= $row[1] ?></td>
                <td width="60%">Rp.<?= $row[2] ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
        `;
    }
    function contentHistory(){
        document.getElementById('content').innerHTML=`
        <h1>History</h1>
        <table border=1>
            <tr>
                <th>No Rekening</th>
                <th>Nama</th>
                <th>Saldo</th>
            </tr>
            <?php
            $sql = "select * from history";
            $query = mysqli_query($koneksi,$sql);
            while($row= mysqli_fetch_array($query)){
            ?>
            <tr>
                <td width="15%"><?= $row[1] ?></td>
                <td width="20%"><?= $row[2] ?></td>
                <td width="60%">Rp.<?= $row[3] ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
        `;

        
    }
    function contentMasterAkun(){
            document.getElementById('content').innerHTML=`
                <h1>Master Akun</h1>
                <form action="masterakun.php" method="post">
            <table>
                <tr>
                    <td><label>No Rekening</label></td>
                    <td>
                        <select name="noreg" id="noreg">
                            <?php
                            $sql = "select * from akun order by nama ASC";
                            $query = mysqli_query($koneksi,$sql);
                            while($row = mysqli_fetch_array($query)){
                            ?>
                            <option value='<?= $row["id_akun"] ?>'><?php echo "$row[nama] - $row[id_akun]"; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Nama</td>
                    <td><input name="nama" placeholder="Masukan nama baru disini" type="text"></td>
                </tr>
                <tr>
                    <td><input name="perbarui" type="submit" value="Perbarui Akun" class="button"></td>
                    <td><input name="delete" type="submit" value="Hapus Akun Permanen" class="button"></td>
                </tr>
            </table>
        </form>
            `;
        }

        function contentMasterUser(){
            document.getElementById('content').innerHTML=`
        <h1>Master User</h1>
        <table border=1>
            <tr>
                <th>Username</th>
                <th>Level</th>
                <th>Aksi</th>
            </tr>
            <?php
            $sql = "select * from user where level=0";
            $query = mysqli_query($koneksi,$sql);
            while($row= mysqli_fetch_array($query)){
            ?>
            <tr>
                <td width="20%"><?= $row[1] ?></td>
                <td width="5%"><?= $row[3] ?></td>
                <td width="20%"><a  href="masteruser.php?accept=<?= $row[0] ?>">Accept</a>
                <a  href="masteruser.php?deny=<?= $row[0] ?>">deny</a></td>
            </tr>
            <?php } ?>
        </table>
    </div>
        `;
        }

        function contentChange(){
            document.getElementById('content').innerHTML = `
                <h1>Ganti Password</h1>
                <form action="change.php" method="post">
                    <table>
                        <tr>
                            <td><label>Password Saat ini</label></td>
                            <td><input type="password" name="oldpass"></td>
                        </tr>
                        <tr>
                            <td><label>Password Baru</label></td>
                            <td><input type="password" name="newpass"></td>
                        </tr>
                        <tr>
                            <td><label>Konfirmasi Password Baru</label></td>
                            <td><input type="password" name="konnewpass"></td>                            
                        </tr>
                        <tr>
                            <td><input type="submit" value="Simpan Perubahaan" name="change" class="button"></td>
                        </tr>
                    </table>
                </form>
                `;
        }
    </script>
</body>

</html>
