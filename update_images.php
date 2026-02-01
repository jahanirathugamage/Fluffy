
$p1 = App\Models\Product::find(1);
if($p1){ $p1->image_path = 'asset/images/cat/whiskas_ocean_fish.webp'; $p1->save(); echo "Updated P1\n"; }

$p2 = App\Models\Product::find(2);
if($p2){ $p2->image_path = 'asset/images/dog/royal_canin_adult.webp'; $p2->save(); echo "Updated P2\n"; }

$p3 = App\Models\Product::where('name', 'like', '%Pro Plan%')->first();
if($p3){ $p3->image_path = 'asset/images/dog/purina_proplan.webp'; $p3->save(); echo "Updated P3\n"; }
