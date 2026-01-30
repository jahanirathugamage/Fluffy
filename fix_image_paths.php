
$products = App\Models\Product::where('image_path', 'like', 'asset/%')->get();
$count = 0;
foreach($products as $p) {
    echo "Updating ID {$p->id}: {$p->image_path} -> ";
    $p->image_path = str_replace('asset/', 'assets/', $p->image_path);
    $p->save();
    echo "{$p->image_path}\n";
    $count++;
}
echo "Updated $count products.\n";
