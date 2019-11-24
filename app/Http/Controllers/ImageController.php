<?php

namespace App\Http\Controllers;

use App\ImageFile;
use Illuminate\Http\Request;
use Image;
use Intervention\Image\Image as Img;

class ImageController extends Controller
{
    /**
     * function store image uploaded.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        if ($request->file) {
            $image = $request->file;
            $name = $image->getClientOriginalName();
            \Image::make($request->file)->save(public_path('images/') . $name);
        }

        $image = new ImageFile();
        $image->image_name = $name;
        $image->Qr = mt_rand(1, 1518488);
        $image->barCode = rand(1111111111, 9999999999);
        $image->save();

        return response()->json(['data' => $image, 'success' => 'You have successfully uploaded an image'], 200);
    }

    /**
     * function search in image when description = keywords.
     *
     * @param Request $request
     *
     * @return array
     */
    public function search(Request $request)
    {
        $error = ['error' => 'No results found, please try with different keywords.'];

        if ($request->has('q')) {
            $images = ImageFile::where('image_name', 'like', '%' . $request->q . '%')->get();

            return $images->count() ? $images : $error;
        }

        return $error;
    }

    /**
     * function get Recent Docs upload limit 5.
     *
     * @return mixed
     */
    public function latestDoc()
    {
        $images = ImageFile::latest()->limit(5)->get();

        return $images;
    }

    public function getImage(Request $request)
    {
        $image = ImageFile::find($request->id);

        return ['data' => $image];
    }

    public function newImage(Request $request)
    {

       // dd($request->images);
        //big image
         $newImage = [];
        foreach ($request->images as $image)
        {

            $img = Image::make(public_path('images').'/'.$image['name'])->resize($image['style']['width'], $image['style']['height'])->rotate($image['rect']['angle']);
            $img->save(public_path('nImage').'/'.$image['name'], 100);
            $newImage[] = $image['name'];


        }


        header("Content-type: image/jpeg");

//number of images to display. Take the first X number of images from the array
        $img_num = 3;

//directory of stored images.
        $src_dir = public_path('nImage');

//total number of images in library, 1 to X
        $numbers = range(1, 21);



//randomize numbers
        shuffle($numbers);

//only use the first X images from the array.
        $numbers = array_slice($numbers, 0, $img_num);


     // dd($numbers[0]);

        $dest_w = 0;
        foreach ($newImage as $key => $val) {
//            dd($val);
//            var_dump($val);

//create the string of where the image is stored
            $src = $src_dir .'/'. $val;
//get image information(size and dimensions)
            $size = getimagesize($src);
          //  dd($size);

//create an image resource based on that jpeg
            $src_gds[$key]['img'] = imagecreatefromjpeg($src);
            // dd($src_gds[$key]['img']);

            $src_gds[$key]['w'] = $size[0];
            $src_gds[$key]['h'] = $size[1];
            $dest_w += $src_gds[$key]['w'];
            $hts[] = $src_gds[$key]['h'];
            //dd($hts);
        }
        $dest_h = max($hts);

//create a new background template of the total size of the combined images
        $dest_gd = imagecreatetruecolor($dest_w, $dest_h);
       // dd($dest_gd);

        $dest_x = 0;
        foreach ($src_gds as $gd) {
            imagecopymerge($dest_gd, $gd['img'], $dest_x, 0, 0, 0, $gd['w'], $gd['h'], 99);
            $dest_x += $gd['w'];
            imagedestroy($gd['img']);
        }
       // dd($dest_gd);

        imagejpeg($dest_gd, public_path('nImage').'/'.$img_num.'.jpg', 100);


       // $file->save(public_path('nImage').'/test.jpeg', 100);

        imagedestroy($dest_gd);
        return $img_num.'.jpg';
    }
}
