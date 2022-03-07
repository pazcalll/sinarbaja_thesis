<?php

use App\Image;
use App\LogStock;
use App\Product;
use App\Stock;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // produk - 1
        $product = Product::create([
            'nama' => 'Body Shop Original parfum',
            'deskripsi' => 1,
            'harga' => '199000',
            'merek' => 'The Body Shop',
            'kategori' => 2,
            'jenis_berbahaya' => 'YA'
        ]);

        $stock = Stock::create([
            'user_id' => 1,
            'product_id' => $product->id,
            'stock' => 11
        ]);

        LogStock::create([
            'stock_id' => $stock->id,
            'type' => 'IN',
            'note' => 'Stock awal produk',
            'before' => 11,
            'current' => 11
        ]);

        Image::create([
            'product_id' => $product->id,
            'path' => '1.jpg'
        ]);

        // produk - 2
        $product = Product::create([
            'nama' => 'Apple iPhone 12 Pro Max 128GB, Pacific Blue',
            'deskripsi' => 'Model HP : iPhone 12 Pro MaxKapasitas : 128GBTipe Kartu SIM : NanoKamera Depan : 12 MPKamera Belakang : 12 MP RAM : 6GB Ukuran Layar : 6, 7 inci Tipe Garansi : Garansi Resmi Warna : Pacific Blue Dimensi : 17,9 x 9, 7 x 2 9 cm Periode Garansi : 1 tahun Isi Kotak : � iPhone dengan iOS 14.� Kabel USB-C ke Lightning.� Buku Manual dan Dokumentasi lain.',
            'harga' => '20499000',
            'merek' => 'Iphone',
            'kategori' => 3,
            'jenis_berbahaya' => 'TIDAK'
        ]);

        $stock = Stock::create([
            'user_id' => 1,
            'product_id' => $product->id,
            'stock' => 46
        ]);

        LogStock::create([
            'stock_id' => $stock->id,
            'type' => 'IN',
            'note' => 'Stock awal produk',
'before' => 46,
            'current' => 46
        ]);

        Image::create([
            'product_id' => $product->id,
            'path' => '2.jpg'
        ]);

        // produk - 3
        $product = Product::create([
            'nama' => 'Love Beauty & Planet Pure And Positive, Tea Tree Oil & Vetiver Body Wash 400Ml',
            'deskripsi' => 'Pure & Positive body wash kami memadukan kandungan Tea Tree Oil dengan keharuman mewah Vetiver, yang membersihkan polutan yang menempel pada tubuhmu dengan lembut, membuatmu merasa segar dan cantik alami. Body Wash ini dikemas dalam 100% botol daur ulang, vegan-certified, cruelty free, bebas dari pewarna, silikon, dan paraben. BPOM NA49180701747 Shelf Life 2 Tahun',
            'harga' => '41700',
            'merek' => 'Love Beauty & Planet',
            'kategori' => 15,
            'jenis_berbahaya' => 'TIDAK'
        ]);

        $stock = Stock::create([
            'user_id' => 1,
            'product_id' => $product->id,
            'stock' => 46
        ]);

        LogStock::create([
            'stock_id' => $stock->id,
            'type' => 'IN',
            'note' => 'Stock awal produk',
'before' => 46,
            'current' => 46
        ]);

        Image::create([
            'product_id' => $product->id,
            'path' => '3.jpg'
        ]);

        // produk - 4
        $product = Product::create([
            'nama' => 'SYIFA VEST BASIC',
            'deskripsi' => 'Material Cotton Waffle All Size Fit To L Detail size: Ld / lingkar dada: 114cm Panjang baju bagian depan (dengan rib depan): 58cm Panjang baju bagian belakang (dengan rib belakang): 64cm Lingkar tangan: 60cm Lingkar leher vest: 68cm',
            'harga' => '56788',
            'merek' => 'Tidak Ada Merek',
            'kategori' => 20,
            'jenis_berbahaya' => 'TIDAK'
        ]);

        $stock = Stock::create([
            'user_id' => 1,
            'product_id' => $product->id,
            'stock' => 4787
        ]);

        LogStock::create([
            'stock_id' => $stock->id,
            'type' => 'IN',
            'note' => 'Stock awal produk',
'before' => 46,
            'current' => 46
        ]);

        Image::create([
            'product_id' => $product->id,
            'path' => '4.jpg'
        ]);

        // produk - 5
        $product = Product::create([
            'nama' => 'Roro Mendut Red Jelly Sleeping Mask',
            'deskripsi' => 'ORIGINAL RORO MENDUT RED JELLY Glowing Skincare Booster NA18200103743 Merupakan gel berbahan dasar herbal untuk perawatan kulit normal/ kering di pa gi & malam hari yang difokuskan untuk menjadikan kulit tampak lebih cerah, glowing dan lembut. Dapat digunakan: Wajah / TubuhMengandung Ekstrak Rosehip dan Aloe Vera Apa saja manfaat Red Jelly: - Mencerahkan - Menyamarkan noda hitam - Menjadikan kulit terasa lebih kencang - Meningkatkan elastisitas kulit - Menyamarkan pori-pori - Memberikan hidrasi pada kulit - Menjadikan kulit terasa lebih lembut Cara Penggunaan: 1. Pastikan kulit dalam kondisi bersih 2. Aplikasikan toner untuk memaksimalkan penyerapan nutrisi krim dan memudahkan pengaplikasian. 3. Ambil gel dan aplikasikan tipis-tipis secara merata 4. Lakukan hal rutin setiap pagi dan malam',
            'harga' => '148410',
            'merek' => 'Roro Mendut',
            'kategori' => 25,
            'jenis_berbahaya' => 'TIDAK'
        ]);

        Stock::create([
            'user_id' => 1,
            'product_id' => $product->id,
            'stock' => 1711
        ]);

        $stock = LogStock::create([
            'stock_id' => $stock->id,
            'type' => 'IN',
            'note' => 'Stock awal produk',
'before' => 46,
            'current' => 46
        ]);

        Image::create([
            'product_id' => $product->id,
            'path' => '5.jpg'
        ]);

        // produk - 6
        $product = Product::create([
            'nama' => 'Sandal Casual Pria Wanita Gunung Hiking Haji dan Umrah Jack V3 LAF Project',
            'deskripsi' => 'Material yang kami gunakan adalah material Premium.',
            'harga' => '199000',
            'merek' => 'LAF Project',
            'kategori' => 30,
            'jenis_berbahaya' => 'TIDAK'
        ]);

        $stock = Stock::create([
            'user_id' => 1,
            'product_id' => $product->id,
            'stock' => 13
        ]);

        LogStock::create([
            'stock_id' => $stock->id,
            'type' => 'IN',
            'note' => 'Stock awal produk',
'before' => 46,
            'current' => 46
        ]);

        Image::create([
            'product_id' => $product->id,
            'path' => '6.jpg'
        ]);

        // produk - 7
        $product = Product::create([
            'nama' => 'KULOT JEANS',
            'deskripsi' => 'Bahan : jeans wash. All size fit to L. L.pingga : 60cm bisa melar sampai 70cm,( bagian belakang karet). L.paha : 54cm. Pj : 90cm',
            'harga' => '265000',
            'merek' => 'H&M',
            'kategori' => 32,
            'jenis_berbahaya' => 'TIDAK'
        ]);

        $stock = Stock::create([
            'user_id' => 1,
            'product_id' => $product->id,
            'stock' => 828
        ]);

        LogStock::create([
            'stock_id' => $stock->id,
            'type' => 'IN',
            'note' => 'Stock awal produk',
'before' => 46,
            'current' => 46
        ]);

        Image::create([
            'product_id' => $product->id,
            'path' => '7.jpg'
        ]);

        // produk - 8
        $product = Product::create([
            'nama' => 'DAMELIA AZALEA SANDALS',
            'deskripsi' => 'Bergaransi 30 Hari. Alas super empuk, tidak akan kempes saat digunakan karena menggunakan Double Foam yang premium. Sol menggunakan non-slip sol, tidak akan licin.',
            'harga' => '90000',
            'merek' => 'Damelia',
            'kategori' => 33,
            'jenis_berbahaya' => 'TIDAK'
        ]);

        $stock = Stock::create([
            'user_id' => 1,
            'product_id' => $product->id,
            'stock' => 1013
        ]);

        LogStock::create([
            'stock_id' => $stock->id,
            'type' => 'IN',
            'note' => 'Stock awal produk',
'before' => 46,
            'current' => 46
        ]);

        Image::create([
            'product_id' => $product->id,
            'path' => '8.jpg'
        ]);

        // produk - 9
        $product = Product::create([
            'nama' => 'Logitech B170 Wireless Mouse',
            'deskripsi' => 'Jenis Koneksi: 2.4 Ghz wireless. Jangkauan wireless: 10 m (32 kaki). Baterai: 1 x AA. Daya tahan baterai: 1 tahun.',
            'harga' => '100000',
            'merek' => 'Logitech',
            'kategori' => 40,
            'jenis_berbahaya' => 'TIDAK'
        ]);

        $stock = Stock::create([
            'user_id' => 1,
            'product_id' => $product->id,
            'stock' => 9049
        ]);

        LogStock::create([
            'stock_id' => $stock->id,
            'type' => 'IN',
            'note' => 'Stock awal produk',
'before' => 46,
            'current' => 46
        ]);

        Image::create([
            'product_id' => $product->id,
            'path' => '9.jpg'
        ]);

        // produk - 10
        $product = Product::create([
            'nama' => 'COSRX AHA/BHA Clarifying Treatment Toner 150ml',
            'deskripsi' => 'pH Level 3.9 sesuai untuk AHA & BHA untuk bekerja dengan efektif. Eksfoliasi yang dapat digunakan sehari-hari, mencegah komedo. Membersihkan pori-pori dan menyeimbangkan kulit.',
            'harga' => '120900',
            'merek' => 'COSRX',
            'kategori' => 50,
            'jenis_berbahaya' => 'TIDAK'
        ]);

        $stock = Stock::create([
            'user_id' => 1,
            'product_id' => $product->id,
            'stock' => 199
        ]);

        LogStock::create([
            'stock_id' => $stock->id,
            'type' => 'IN',
            'note' => 'Stock awal produk',
'before' => 46,
            'current' => 46
        ]);

        Image::create([
            'product_id' => $product->id,
            'path' => '10.jpg'
        ]);

        // produk - 11
        $product = Product::create([
            'nama' => 'Lip Glaze BLP - Cranberry Cobbler',
            'deskripsi' => 'Let your lips take center stage with Cranberry Cobbler. The applicator allows you to shape and color your lips with this bold note at ease. This one is inspired by the crowd favorite, Burnt Cinnamon.',
            'harga' => '139000',
            'merek' => 'BLP Beauty',
            'kategori' => 50,
            'jenis_berbahaya' => 'TIDAK'
        ]);

        $stock = Stock::create([
            'user_id' => 1,
            'product_id' => $product->id,
            'stock' => 643
        ]);

        LogStock::create([
            'stock_id' => $stock->id,
            'type' => 'IN',
            'note' => 'Stock awal produk',
'before' => 46,
            'current' => 46
        ]);

        Image::create([
            'product_id' => $product->id,
            'path' => '11.jpg'
        ]);

        // produk - 12
        $product = Product::create([
            'nama' => 'Lip Glaze BLP - Cranberry Sober',
            'deskripsi' => 'Let your lips take center stage with Cranberry Cobbler. The applicator allows you to shape and color your lips with this bold note at ease. This one is inspired by the crowd favorite, Burnt Cinnamon.',
            'harga' => '139000',
            'merek' => 'BLP Beauty',
            'kategori' => 60,
            'jenis_berbahaya' => 'TIDAK'
        ]);

        $stock = Stock::create([
            'user_id' => 1,
            'product_id' => $product->id,
            'stock' => 643
        ]);

        LogStock::create([
            'stock_id' => $stock->id,
            'type' => 'IN',
            'note' => 'Stock awal produk',
            'before' => 46,
            'current' => 46
        ]);

        Image::create([
            'product_id' => $product->id,
            'path' => '11.jpg'
        ]);


    }
}
