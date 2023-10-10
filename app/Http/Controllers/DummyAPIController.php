<?php

namespace App\Http\Controllers;

use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\Feature\Type;
use Google\Cloud\Vision\V1\Likelihood;
use Illuminate\Http\Request;


class DummyAPIController extends Controller
{
    public function getImageEmotions(Request $request){
        
        $request->validate([
                    'imageUrl' => 'required|url', // Validate that "imageUrl" is required
                ]);
        
                $imageUrl = $request->input('imageUrl');

        base64_encode($imageUrl);



        // Initialize the ImageAnnotatorClient
        $client = new ImageAnnotatorClient();
    

        // Annotate the image for face detection
        $image = $client->annotateImage(
            $imageUrl,
            [Type::FACE_DETECTION]
        );
    
        // Create an array to store emotions and likelihood values
        $emotions = [];
    
        foreach ($image->getFaceAnnotations() as $faceAnnotation) {
            $emotions[] = [
                'emotion' => 'Anger',
                'likelihood' => Likelihood::name($faceAnnotation->getAngerLikelihood()),
            ];
    
            $emotions[] = [
                'emotion' => 'Joy',
                'likelihood' => Likelihood::name($faceAnnotation->getJoyLikelihood()),
            ];
    
            $emotions[] = [
                'emotion' => 'Sorrow',
                'likelihood' => Likelihood::name($faceAnnotation->getSorrowLikelihood()),
            ];
    
            $emotions[] = [
                'emotion' => 'Surprise',
                'likelihood' => Likelihood::name($faceAnnotation->getSurpriseLikelihood()),
            ];
        }
    
        // Close the ImageAnnotatorClient
        $client->close();

        // Encode the emotions array as JSON and return it
        return response()->json($emotions);
    }


}