<?php
/**
* ����� ��� ��������� ����������� �� �������
* ���������� GD Graphics Library
*/
abstract class Image {

    /**
	* ���������������� ��������������� � �������
	*
	* �������� � JPEG, PNG, � GIF.
	* ��������������� ������������ ���, ����� ����������� ������ � �������� �������������. ��������� ������ �� ����������.
    *
	* @params array  - ���������
	* @return string - ��� ��������� ����� ��� false � ������ ������
	*/
	public function edit($params) {
    
        // ��������� ��-���������
        $prm = array(
            'fit'         => 'cover', //'cover' - ���������� ����� ��������� ��� �������, 'contain' - ������� ��������� � �������� �������������, true - �� ��, �� ������� ����������� �� ��������������, false - ����������� �������� �������                                                 
            'width'       => 1000,    // ������ �������������� � px, ���� 0, �� �����������
            'height'      => 1000,    // ������ �������������� � px, ���� 0, �� �����������
            'ext'         => 'jpg',   // ��� ��������� ����� jpeg, png ��� gif. ��� null ��� ������������ �� ����� ��������� �����. � ��������� ������ �� ���� ���������
            'quality'     => 75,      // �������� ��������� �����. 1�100 ��� JPEG (������������� 75), 0�9 ��� PNG (������������� 9, ���� ������ �������� �������������� ��������)
            'copyright'   => false,   // ����� �� ������� ��������. ����������� ������� �� ����� copyright.png
            'w_pct'       => 1,       // ������ ������� ������� � %*0.01
            'h_pct'       => 1,       // ������ ������� ������� � %*0.01
            'x_pct'       => null,    // X-���������� ������ �������� ���� ������� ������� � %*0,01. ��� null ������� ������� ������������ ��-������
            'y_pct'       => null,    // Y-���������� ������ �������� ���� ������� ������� � %*0,01. ��� null ������� ������� ������������ ��-������
            'file_input'  => null,    // ������������ ��������� �����
            'file_output' => null     // ������������ ��������� �����, ���� ����������� false, �� �������� ���� ���������������� �����
        );
        $prm = array_merge($prm, $params);
        
		list($w, $h, $type) = getimagesize($prm['file_input']);
		if (!$w || !$h) return false; //���������� �������� ����� � ������ �����������
        
        if ($prm['fit'] !== 'cover') {
            //��������� ����� ������� �����������, ���� ��� �� �����������
            $h1 = $h;
            if ($prm['width'] && is_numeric($prm['width']) && ($w > $prm['width'] || $prm['fit'] === 'contain')) {
                $new_w = $prm['width'];
                $new_h = $h1 = $new_w/($w/$h);
            }	
            if ($prm['height'] && is_numeric($prm['height']) && $h1 > $prm['height']) {
                $new_h = $prm['height'];
                $new_w = $new_h/($h/$w);
            }       
              
            //���� ������� �� ���������� (��� �� ������ ��������) � �� ���� ������� ��������, ������ �������� ����
            if ((!$new_w ||!$prm['fit']) && !$prm['copyright']) return self::convert($prm['file_input'], $prm['file_output'], null, $prm['ext'], $prm['quality']);

            if (!$new_w || !$prm['fit']) {             
                //���� ������� �� ����������, ��������� �� �������
                $new_w = $w;
                $new_h = $h;                 
            } 
        } else {
            $new_w = intval($prm['width']);
            $new_h = intval($prm['height']); 
        }
            
        //������ ������ �� ��������� �����������
        switch ($type) {
            case IMAGETYPE_JPEG: $image = imagecreatefromjpeg($prm['file_input']); break;
            case IMAGETYPE_PNG:  $image = imagecreatefrompng($prm['file_input']); break;
            case IMAGETYPE_GIF:  $image = imagecreatefromgif($prm['file_input']); break;
            default: echo '������������ ������ �����'; return false; //������������ ������ �����
        }
        
        //������� ����� �����������	� ������ ��� �������
        $new_image = imagecreatetruecolor($new_w, $new_h);
        
        if ($prm['fit'] === 'cover') {           
            //��������� ������������ ��������� ������� �������
            if ($prm['w_pct'] <= 0 || $prm['w_pct'] > 1) $prm['w_pct'] = 1;
            if ($prm['h_pct'] <= 0 || $prm['h_pct'] > 1) $prm['h_pct'] = 1;
            if (!is_numeric($prm['x_pct']) || $prm['x_pct'] < 0 || $prm['x_pct'] >= 1) $prm['x_pct'] = (1 - $prm['w_pct']) / 2;
            if (!is_numeric($prm['y_pct']) || $prm['y_pct'] < 0 || $prm['y_pct'] >= 1) $prm['y_pct'] = (1 - $prm['h_pct']) / 2;
            
            //��������� �������� � �������
            $src_w = $w*$prm['w_pct'];
            $src_h = $h*$prm['h_pct'];
            $src_x = min($w*$prm['x_pct'], $w-$src_w);
            $src_y = min($h*$prm['y_pct'], $h-$src_h);

            //����������� ������� ������
            $src_w_new = $src_h*$new_w/$new_h;
            $src_h_new = $src_w*$new_h/$new_w;
            
            //��������� ������� �� ������ ��������
            if ($src_w > $src_w_new) {
                $src_x += ($src_w - $src_w_new) / 2;
                $src_w = $src_w_new;
            } else {
                $src_y += ($src_h - $src_h_new) / 2;
                $src_h = $src_h_new;
            }

            //������� ���������
            imagecopyresampled($new_image, $image, 0, 0, $src_x, $src_y, $new_w, $new_h, $src_w, $src_h);
        } else {
            imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_w, $new_h, $w, $h); 
        }
        imagedestroy($image);
         
		//������ ��������
		if ($prm['copyright']) {
			$file_copyright = 'copyright.png';
			list($cw, $ch) = getimagesize($file_copyright);
			
			$copyright_image = imagecreatefrompng($file_copyright);
			imagecopy($new_image, $copyright_image ,$new_w-$cw, $new_h-$ch, 0, 0, $cw, $ch);
			imagedestroy($copyright_image);
		}

		//���������
		return self::convert($prm['file_input'], $prm['file_output'], $new_image, $prm['ext'], $prm['quality']);
	}
	
	/**
	* ���������� ����������� � JPEG, PNG ��� GIF.
	*
	* �������:
	* convert('img/flower.png', 'thumb/astra.png')                   //��������� img/flower.png ��� ������ thumb/astra.png
	* convert('img/flower.png', 'thumb/astra.jpeg')                  //��������� � ��������������� � jpeg � ��������� �� ���������
	* convert('img/flower.png', 'thumb/',          null,   jpeg)     //��������� � ��������������� � jpeg ��� ������ thumb/flower.jpeg
	* convert('img/flower.png',  null,             null,   jpeg, 70) //����������� � jpeg � ��������� 70
	* convert('img/flower.png',  null,             $image, jpeg)     //�������� $image ��� ������ img/flower.jpeg, ������ ����� ���� img/flower.png 
	* convert( null,            'thumb/astra.png', $image)           //�������� $image ��� ������ 'thumb/astra.png'
	*
	* @param string ��� ��������� �����������
	* @param string ��� ��������� �����������, ���� ����������� false, �� �������� ���� ���������������� �����
	* @param object �������� ��������� �����������
	* @param string ��� ��������� ����� jpeg, png ��� gif. ��� null ��� ������������ �� ����� ��������� �����. � ��������� ������ �� ���� ���������
	* @param integer �������� ��������� �����. 1�100 ��� JPEG (������������� 75), 0�9 ��� PNG (������������� 9, ���� ������ �������� �������������� ��������)
	* @return string ��� ��������� ����� ��� false � ������ ������
	*/
	public function convert($file_input, $file_output = false, $image, $ext = null, $quality = null) {

		//���������� ��� ��������� �����.
		//���� ��� ����� �� ������, �� ����� ���� �� ����� ��������� �����, ���� �� ��� ���, �� �� ���� ���������
		list($w, $h, $type) = getimagesize($file_input);
		$file_input_ext = image_type_to_extension($type, false);
		$file_output_ext = pathinfo($file_output, PATHINFO_EXTENSION);
		
		if (!$ext && !$file_output_ext) {
			$ext = $file_input_ext;
		} elseif (!$ext) {
			$ext = $file_output_ext;
		}
		
		$ext = strtolower($ext);
		
		//���� �������� ����������� ����, �� ��������� �������������� � ������ ������, ��������� � �������� �������� ���� 
		if (!$image && $file_input_ext != $ext) {
			switch ($type) {
				case IMAGETYPE_JPEG: $image = imagecreatefromjpeg($file_input); break;
				case IMAGETYPE_PNG:  $image = imagecreatefrompng($file_input); break;
				case IMAGETYPE_GIF:  $image = imagecreatefromgif($file_input); break;
				default: return false; //���� ������������� ����
			}
		}
	
		//���� ����������� �� ��������� � � ��������� ���� �����������, ������� ������ ����
		if (!$file_output || $file_output == $file_input) {
			if ($image) unlink($file_input);
			$file_output = $file_input;
			$fixed = true;
		}
		
		//���������� ��� � ���� ��� ������ �����
		$path = pathinfo($file_output, PATHINFO_DIRNAME).'/';
		$name = pathinfo($file_output, PATHINFO_FILENAME).'.';

		//���� �������������� �� ���������, ������ �������� ����
		if (!$image) {
			if (!$fixed) {
				if (!copy($file_input, $path.$name.$ext)) return false; //�� ������� �����������
			}
			return $name.$ext;
		}
		
		//����������� � ���������
		switch ($ext) {
			case 'jpeg':
			case 'jpg':
				$ext = 'jpeg';
				if (!is_numeric($quality) || $quality < 1 || $quality > 100) $quality = 75;
				if (!imagejpeg($image, $path.$name.$ext, $quality)) return false; //�� ������� ��������� � jpeg
				break;
				
			case 'gif':
				if (!imagegif($image, $path.$name.$ext)) return false; //�� ������� ��������� � gif
				break;
				
			default:
				$ext = 'png';
				if (!is_numeric($quality) || $quality < 1 || $quality > 100) $quality = 9;
				$quality = round($quality / 11.111111);
				if (!imagepng($image, $path.$name.$ext, $quality)) return false; //�� ������� ��������� � png
		}
		imagedestroy($image);
		
		return $name.$ext;
	}
}
?>