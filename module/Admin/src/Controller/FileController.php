<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin\Controller;

use Admin\Entity\File;
use Admin\Form\ImageForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class FileController extends AbstractActionController {

    private $entityManager;

    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
    }

	function scale_image($src_image, $dst_image, $op = 'fit') {
		$src_width = imagesx($src_image);
		$src_height = imagesy($src_image);

		$dst_width = imagesx($dst_image);
		$dst_height = imagesy($dst_image);

		// Try to match destination image by width
		$new_width = $dst_width;
		$new_height = round($new_width*($src_height/$src_width));
		$new_x = 0;
		$new_y = round(($dst_height-$new_height)/2);

		// FILL and FIT mode are mutually exclusive
		if ($op =='fill')
			$next = $new_height < $dst_height;
		else
			$next = $new_height > $dst_height;

		// If match by width failed and destination image does not fit, try by height
		if ($next) {
			$new_height = $dst_height;
			$new_width = round($new_height*($src_width/$src_height));
			$new_x = round(($dst_width - $new_width)/2);
			$new_y = 0;
		}

		// Copy image on right place
		imagecopyresampled($dst_image, $src_image , $new_x, $new_y, 0, 0, $new_width, $new_height, $src_width, $src_height);
	}

	public function scaleImageFileToBlob($file) {

		$source_pic = $file;
		$max_width = 60;
		$max_height = 45;

		list($width, $height, $image_type) = getimagesize($file);

		switch ($image_type)
		{
			case 1: $src = imagecreatefromgif($file); break;
			case 2: $src = imagecreatefromjpeg($file);  break;
			case 3: $src = imagecreatefrompng($file); break;
			default: return '';  break;
		}

		$x_ratio = $max_width / $width;
		$y_ratio = $max_height / $height;

		if( ($width <= $max_width) && ($height <= $max_height) ){
			$tn_width = $width;
			$tn_height = $height;
		}elseif (($x_ratio * $height) < $max_height){
			$tn_height = ceil($x_ratio * $height);
			$tn_width = $max_width;
		}else{
			$tn_width = ceil($y_ratio * $width);
			$tn_height = $max_height;
		}

		$tmp = imagecreatetruecolor($tn_width,$tn_height);

		/* Check if this image is PNG or GIF, then set if Transparent*/
		if(($image_type == 1) OR ($image_type==3))
		{
			imagealphablending($tmp, false);
			imagesavealpha($tmp,true);
			$transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
			imagefilledrectangle($tmp, 0, 0, $tn_width, $tn_height, $transparent);
		}
		imagecopyresampled($tmp,$src,0,0,0,0,$tn_width, $tn_height,$width,$height);

		/*
		 * imageXXX() only has two options, save as a file, or send to the browser.
		 * It does not provide you the oppurtunity to manipulate the final GIF/JPG/PNG file stream
		 * So I start the output buffering, use imageXXX() to output the data stream to the browser,
		 * get the contents of the stream, and use clean to silently discard the buffered contents.
		 */
		ob_start();

		switch ($image_type)
		{
			case 1: imagegif($tmp); break;
			case 2: imagejpeg($tmp, NULL, 100);  break; // best quality
			case 3: imagepng($tmp, NULL, 0); break; // no compression
			default: echo ''; break;
		}

		$final_image = ob_get_contents();

		ob_end_clean();

		return $final_image;
	}

    public function indexAction() {

        $form = new ImageForm();

        if ($this->getRequest()->isPost()) {
            $request = $this->getRequest();
            $data = $request->getFiles()->toArray();

            $form->setData($data);

            if ($form->isValid()) {
                $data = $form->getData();
            }


            $prepareFiles = [];

            if (!empty($data['files'])) {
                foreach ($data['files'] as $file) {

					$thumb = $this->scaleImageFileToBlob('data/files/' . $file['name']);

                    $f = new File();
                    $f->setName($file['name']);
                    $f->setSize($file['size']);
                    $f->setThumb($thumb);
                    $f->setType($file['type']);
                    $this->entityManager->persist($f);
                    $this->entityManager->flush();

                    $prepareFiles[] = [
                        'name' => $file['name'],
                        'size' => $file['size'],
                        'url' => '/images/' . $file['name'],
                        'thumbnailUrl' => '/images/thumb/' . $file['name'],
                        'deleteUrl' => '/images/delete/' . $file['name'],
                        'deleteType' => 'DELETE',
                    ];
                }
            }

            return new JsonModel([
                'files' => $prepareFiles
            ]);

//            echo '<pre>'.print_r($data,true).'</pre>';

        }

        return new ViewModel();
    }

    public function addAction() {

		$res = $this->entityManager->getRepository(File::class)->findOneBy(['id'=>9]);
		$f = base64_encode($res->getThumb());
		echo "<img src='data:image/jpeg;base64,{$f}' />";

//        return new JsonModel([
//            'v' => 'asd'
//        ]);
    }

    public function editAction() {


    }
}
