<?php
/* func_gallery.php
Gallerian funktiot
*/
function imageInformation($originalFile)
{
    // Ottaa tarkistettavan tiedoston nimen ja tarkistaa sen tiedostop��tteen v�litt�m�tt� tiedostonimest�, toimii kuville
    $type = getimagesize($originalFile);
    $filesize = filesize($originalFile);
   
    // Tarkistetaan tiedoston tyyppi
	
    if($type[2] == 1) // GIF
    {
        $fileExtension = "gif";
    }
    elseif($type[2] == 2) // JPEG
    {
        $fileExtension = "jpg";
    }
    elseif($type[2] == 3) // PNG
    {
        $fileExtension = "png";
    }
    else // Tiedostomuoto ei ole tuettu, palauttaa FALSE
    {
        $fileExtension = FALSE;
    }
    // Funktio palauttaa arvot, jos ok
    if($fileExtension)
    {
        // palauttaa type,tiedostop��te,leveys,korkeus,tiedostokoko
        return array($type[2],$fileExtension, $type[0], $type[1], $filesize);
    }
    else
    {
        // Tiedostotyyppi ei ole tuettu tai jotain h�iriöt�
        return array(FALSE, FALSE, FALSE, FALSE);
        }
    }

function createResizedImage($originalFile, $destinationFile, $resizeWidth, $resizedHeight)
{
    // Ottaa sy�tteen� vastaan (alkuper�inen tiedosto), (uuden kuvan hakemisto/tiedosto ilman p��tett�), (uusi leveys), (uusi korkeus)
   
    // Selvitet��n kuvan koko ja tyyppi
    list($originalWidth, $originalHeight, $type) = getimagesize($originalFile);
   
    // Tarkistetaan tiedoston tyyppi
    if($type == 1) // GIF
    {
    	$originalFile = imagecreatefromgif($originalFile);
        // Lapinakyvyys -> valkoinen
        $white = imagecolorallocate($originalImage, 255, 255, 255);
        $transparent = imagecolortransparent($originalFile, $white);
    }
    elseif($type == 2) // JPEG
    {
    	$originalFile = imagecreatefromjpeg($originalFile);
    }
    elseif($type == 3) // PNG
    {
    	$originalFile = imagecreatefrompng($originalFile);
    }
    else // Tiedostomuoto ei ole tuettu, palauttaa FALSE
    {
        $type = FALSE;
    }
    if($type)
    {
        // Lasketaan kuvalle uusi koko siten, ett� kuvasuhde s�ilyy
        $newW = $originalWidth/$resizedWidth; // Kuvasuhde: leveys
        $newH = $originalHeight/$resizedHeight; // Kuvasuhde: korkeus
        if($newW > $new_h || $newW == $new_h)
        {
            if($newW < 1)
            {
                // Jos alkuperainen kuva on pienempi kuin luotava, luodaan alkuperaisen kokoinen kuva
                $newW = 1;
            }
            // K�ytet��n sit� suhdetta, jolla tulee max. asetettu leveys, korkeus on alle max.
            $newWidth = $originalWidth / $newW;
            $newHeight = $originalHeight / $newW;
        }
        elseif($newW < $newH)
        {
            if($newH < 1)
            {
                // Jos alkuper�inen kuva on pienempi kuin luotava, luodaan alkuper�isen kokoinen kuva
                $newH = 1;
            }
            // K�ytet��n sit� suhdetta, jolla tulee max. asetettu korkeus, leveys on alle max.
            $newWidth = $originalWidth / $new_h;
            $newHeight = $originalHeight / $new_h;
        }
        // Luodaan kuva, joka on m��r�tyn kokoinen
        $image = imagecreatetruecolor($newWidth, $newHeight);
		
        // Resample, luo uuden kuvan tiedostoon
        imagecopyresampled(
        		$image,
        		$originalImage,
        		0,
        		0,
        		0,
        		0,
        		$newWidth,
        		$newHeight,
        		$originalWidth,
        		$originalHeight
        		);
       
        // Tallennetaan uusi kuva m��riteltyyn tiedostoon ja annetaan sopiva tiedostop��te
        if($type == 1) // GIF
        {
            imagegif($image, $destinationFile);
        }
        elseif($type == 2) // JPEG
        {
            imagejpeg($image, $destinationFile);
        }
        elseif($type == 3) // PNG
        {
            imagepng($image, $destinationFile);
        }
    }
    // Poistetaan kuva muistista, ei tuhoa alkuper�ist� tiedostoa!
    imagedestroy($image);
    // Palauttaa tiedostotyypin onnistuessaan, FALSE jos ei onnistu
    return $type;
    }
    
    
function luoKansio($maakansio, $galleriakansio) 
{  
  $ok = FALSE; 
  
  /* luo galleriakansio ja pura suojaukset*/
  $polku = "./sisaltokuvat/".$maakansio."/".$galleriakansio;
  if(mkdir($polku, 0777)) 
	$ok = TRUE;
  
  /*luo alikansiot thumbs, upload ja kuvat*/
  $thumbpolku = $polku."/thumbs";
  $kuvatpolku = $polku."/kuvat";
  $uploadpolku = $polku."/upload";
  if($ok)
  {
	 if(mkdir($thumbpolku, 0755) && mkdir($kuvatpolku, 0755) && mkdir($uploadpolku, 0755)) $ok=TRUE;
  }
  suojaaKansio($polku);
  return $ok;
}

function puraSuojaus($kansio)
{
	chmod($kansio, 0777);
}

function suojaaKansio($kansio)
{
	chmod($kansio, 0755);
}

?>
