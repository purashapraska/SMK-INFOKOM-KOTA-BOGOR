<?php

declare(strict_types=1);

/**
 * Initial Guru & Staff dataset used only when the teachers table is empty.
 * After the first sync, the database becomes the single source of truth.
 */
function default_teacher_seed_data(): array
{
    return [
        ['Ir. Hj. Liliek Rahmaningsih, M.M.Pd.', 'assets/images/guru/ir-hj-liliek-rahmaningsih-m-m-pd.png', 'Kepala Sekolah', 'Manajemen Pendidikan', 'S2', 'Pimpinan sekolah yang mengarahkan pengembangan pembelajaran, karakter, dan layanan pendidikan di SMK INFOKOM Kota Bogor.'],
        ['Agus Tiyono, M.Pd.', 'assets/images/guru/agus-tiyono-m-pd.png', 'Wakil Kepala Sekolah Bidang Sarana dan Prasarana', 'Sarana & Prasarana', 'S2', 'Bagian dari tim pimpinan yang mendukung pengelolaan sarana dan prasarana sekolah.'],
        ['Dea Rijalul Fikri, S.Kom.,Gr.', 'assets/images/guru/dea-rijalul-fikri-s-kom-gr.png', 'Wakil Kepala Sekolah Bidang Kesiswaan', 'Manajemen Kesiswaan', 'S1', 'Mendukung pembinaan siswa, kegiatan kesiswaan, dan pengembangan lingkungan belajar yang positif.'],
        ['Heny Handayani, S.Pd.', 'assets/images/guru/heny-handayani-s-pd.png', 'Wakil Kepala Sekolah Bidang Pengembangan Sumber Daya Manusia', 'Pengembangan Sumber Daya Manusia', 'S1', 'Mendukung pengembangan sumber daya manusia dan kolaborasi internal sekolah.'],
        ['Mila Yaelasari, M.Pd.', 'assets/images/guru/mila-yaelasari-m-pd.png', 'Wakil Kepala Sekolah Bidang Kurikulum', 'Manajemen Kurikulum', 'S2', 'Mendukung pengelolaan kurikulum dan peningkatan kualitas proses pembelajaran.'],
        ['R. Radiani Srirestuti Dewi, SS., Gr.', 'assets/images/guru/r-radiani-srirestuti-dewi-ss-gr.png', 'Wakil Kepala Sekolah Bidang Hubungan Industri dan Masyarakat', 'Hubungan Industri & Masyarakat', 'S1', 'Mendukung hubungan sekolah dengan masyarakat dan dunia industri.'],
        ['Abdul Azis, M.Pd.', 'assets/images/guru/abdul-azis-m-pd.png', 'Guru', 'Pendidik', 'S2', 'Tenaga pendidik yang mendampingi peserta didik dalam proses belajar dan pengembangan kompetensi.'],
        ['Aditya Nugraha, ST.', 'assets/images/guru/aditya-nugraha-st.png', 'Guru', 'Pendidik', 'S1', 'Tenaga pendidik yang mendampingi peserta didik dalam proses belajar dan pengembangan kompetensi.'],
        ['Ahmad Hotib, S.Pd.I', 'assets/images/guru/ahmad-hotib-s-pd-i.png', 'Guru', 'Pendidik', 'S1', 'Tenaga pendidik yang mendampingi peserta didik dalam proses belajar dan pengembangan kompetensi.'],
        ['Cahyadi Rozi, ST.', 'assets/images/guru/cahyadi-rozi-st.png', 'Guru', 'Pendidik', 'S1', 'Tenaga pendidik yang mendampingi peserta didik dalam proses belajar dan pengembangan kompetensi.'],
        ['Dara Purwanita, S.Pd., Gr.', 'assets/images/guru/dara-purwanita-s-pd-gr.png', 'Guru', 'Pendidik', 'S1', 'Tenaga pendidik yang mendampingi peserta didik dalam proses belajar dan pengembangan kompetensi.'],
        ['Dian Hardianti, S.Kom.,Gr.', 'assets/images/guru/dian-hardianti-s-kom-gr.png', 'Guru', 'Pendidik', 'S1', 'Tenaga pendidik yang mendampingi peserta didik dalam proses belajar dan pengembangan kompetensi.'],
        ['Dra. Diana Octaria', 'assets/images/guru/dra-diana-octaria.png', 'Guru', 'Pendidik', 'S1', 'Tenaga pendidik yang mendampingi peserta didik dalam proses belajar dan pengembangan kompetensi.'],
        ['Dra. Popon Puspitasari', 'assets/images/guru/dra-popon-puspitasari.png', 'Guru', 'Pendidik', 'S1', 'Tenaga pendidik yang mendampingi peserta didik dalam proses belajar dan pengembangan kompetensi.'],
        ['Drs. Puji Marhaen Waluyo', 'assets/images/guru/drs-puji-marhaen-waluyo.png', 'Guru', 'Pendidik', 'S1', 'Tenaga pendidik yang mendampingi peserta didik dalam proses belajar dan pengembangan kompetensi.'],
        ['Erli Suherli, A.Md.Kom.', 'assets/images/guru/erli-suherli-a-md-kom.png', 'Guru', 'Pendidik', 'D3', 'Tenaga pendidik yang mendampingi peserta didik dalam proses belajar dan pengembangan kompetensi.'],
        ['Erwin Hasiholan Gultom, S.Kom.', 'assets/images/guru/erwin-hasiholan-gultom-s-kom.png', 'Guru', 'Pendidik', 'S1', 'Tenaga pendidik yang mendampingi peserta didik dalam proses belajar dan pengembangan kompetensi.'],
        ['Eva Farida Rahayu, S.Pd., Gr.', 'assets/images/guru/eva-farida-rahayu-s-pd-gr.png', 'Guru', 'Pendidik', 'S1', 'Tenaga pendidik yang mendampingi peserta didik dalam proses belajar dan pengembangan kompetensi.'],
        ['Muhammad Ibnu Arif, S.IKOM.', 'assets/images/guru/muhammad-ibnu-arif-s-ikom.png', 'Guru', 'Pendidik', 'S1', 'Tenaga pendidik yang mendampingi peserta didik dalam proses belajar dan pengembangan kompetensi.'],
        ['Nabila Ahmadia Pratama, S.Pd.', 'assets/images/guru/nabila-ahmadia-pratama-s-pd.png', 'Guru', 'Pendidik', 'S1', 'Tenaga pendidik yang mendampingi peserta didik dalam proses belajar dan pengembangan kompetensi.'],
        ['Putri Riandini, S.Hut., M.Pd.,Gr.', 'assets/images/guru/putri-riandini-s-hut-m-pd-gr.png', 'Guru', 'Pendidik', 'S2', 'Tenaga pendidik yang mendampingi peserta didik dalam proses belajar dan pengembangan kompetensi.'],
        ['Richo Santana, S.Kom.', 'assets/images/guru/richo-santana-s-kom.png', 'Guru', 'Pendidik', 'S1', 'Tenaga pendidik yang mendampingi peserta didik dalam proses belajar dan pengembangan kompetensi.'],
        ['Ridwan Hala, S.Kom., Gr.', 'assets/images/guru/ridwan-hala-s-kom-gr.png', 'Guru', 'Pendidik', 'S1', 'Tenaga pendidik yang mendampingi peserta didik dalam proses belajar dan pengembangan kompetensi.'],
        ['Vera Yuni Astuti, SP., M.Ikom.,Gr.', 'assets/images/guru/vera-yuni-astuti-sp-m-ikom-gr.png', 'Guru', 'Pendidik', 'S2', 'Tenaga pendidik yang mendampingi peserta didik dalam proses belajar dan pengembangan kompetensi.'],
        ['Widyatama Nuranisa, SM.', 'assets/images/guru/widyatama-nuranisa-sm.png', 'Guru', 'Pendidik', 'S1', 'Tenaga pendidik yang mendampingi peserta didik dalam proses belajar dan pengembangan kompetensi.'],
        ['Yeni Yuliawati, S.Pd., Gr.', 'assets/images/guru/yeni-yuliawati-s-pd-gr.png', 'Guru', 'Pendidik', 'S1', 'Tenaga pendidik yang mendampingi peserta didik dalam proses belajar dan pengembangan kompetensi.'],
        ['Jayadi, S.Pd., Gr.', 'assets/images/guru/jayadi-s-pd-gr.png', 'Tata Usaha', 'Tata Usaha', 'S1', 'Mendukung administrasi dan layanan operasional sekolah.'],
        ['M. Fauzan Arifin, S.IKom.,Gr.', 'assets/images/guru/m-fauzan-arifin-s-ikom-gr.png', 'Staf Kurikulum', 'Kurikulum', 'S1', 'Mendukung pengelolaan administrasi dan layanan kurikulum sekolah.'],
        ['Reza Prafitriansyah, S.Kom.,Gr.', 'assets/images/guru/reza-prafitriansyah-s-kom-gr.png', 'Perpustakaan', 'Perpustakaan', 'S1', 'Mendukung layanan perpustakaan dan akses sumber belajar bagi warga sekolah.'],
        ['Rita Widyastuti', 'assets/images/guru/rita-widyastuti.png', 'Bendahara', 'Keuangan', 'S1', 'Mendukung pengelolaan administrasi dan layanan keuangan sekolah.'],
        ['Siti Nuraeni', 'assets/images/guru/siti-nuraeni.png', 'Bendahara', 'Keuangan', 'S1', 'Mendukung pengelolaan administrasi dan layanan keuangan sekolah.'],
        ['Yadi Setiadi, S.Sos., Gr.', 'assets/images/guru/yadi-setiadi-s-sos-gr.png', 'Tata Usaha', 'Tata Usaha', 'S1', 'Mendukung administrasi dan layanan operasional sekolah.'],
    ];
}
