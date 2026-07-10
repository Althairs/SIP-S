<?php

return [
    'templates' => [
        [
            'name' => 'Pendaftaran Berhasil',
            'message' => "Assalamualaikum Wr. Wb.\n\nYth. {name}\n\nSelamat! Pendaftaran {jenis_ujian} Anda dengan judul \"{judul}\" telah berhasil diverifikasi.\n\nTanggal Ujian: {tanggal}\nRuangan: {ruangan}\nSesi: {sesi}\n\nMohon hadir tepat waktu.\n\nTerima kasih."
        ],
        [
            'name' => 'Jadwal Ujian',
            'message' => "Assalamualaikum Wr. Wb.\n\nYth. {name}\n\nBerikut jadwal ujian Anda:\n\nJenis Ujian: {jenis_ujian}\nJudul: {judul}\nTanggal: {tanggal}\nRuangan: {ruangan}\nSesi: {sesi}\n\nTerima kasih."
        ],
        [
            'name' => 'Revisi Skripsi',
            'message' => "Assalamualaikum Wr. Wb.\n\nYth. {name}\n\nMohon segera melakukan revisi {jenis_ujian} dengan judul \"{judul}\".\n\nCatatan Revisi:\n{catatan_revisi}\n\nDeadline revisi: {deadline}\n\nTerima kasih."
        ],
        [
            'name' => 'Pengingat Ujian',
            'message' => "Assalamualaikum Wr. Wb.\n\nYth. {name}\n\nIni adalah pengingat bahwa ujian {jenis_ujian} Anda akan dilaksanakan besok.\n\nJudul: {judul}\nTanggal: {tanggal}\nRuangan: {ruangan}\nSesi: {sesi}\n\nMohon persiapkan diri Anda.\n\nTerima kasih."
        ],
        [
            'name' => 'Informasi Umum',
            'message' => "Assalamualaikum Wr. Wb.\n\nYth. {name}\n\n{message}\n\nTerima kasih."
        ],
        [
            'name' => 'Custom Template',
            'message' => "Halo {name},\n\nIni adalah pesan custom:\n{message}\n\nTerima kasih."
        ]
    ]
];
