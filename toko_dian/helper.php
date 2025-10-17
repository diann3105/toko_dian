<?php
function nextKode($koneksi,$tabel,$kolomid,$prefix,$pad=3){
  $r = mysqli_query($koneksi,"SELECT MAX($kolomid) kode FROM $tabel");
  $d = mysqli_fetch_assoc($r);
  $max = $d['kode'] ?: ($prefix.str_pad('0',$pad,'0'));
  $num = (int)substr($max, strlen($prefix)); $num++;
  return $prefix . str_pad((string)$num, $pad, '0', STR_PAD_LEFT);
}
?>
