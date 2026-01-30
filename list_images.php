
$products = App\Models\Product::all();
foreach($products as $p) {
    echo "ID: {$p->id} | Name: {$p->name} | Path: {$p->image_path}\n";
}
