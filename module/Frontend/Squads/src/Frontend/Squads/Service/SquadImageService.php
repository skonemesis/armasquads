<?php
namespace Frontend\Squads\Service;

use Frontend\Squads\Entity\Squad;

class SquadImageService
{
    public function getServerSquadLogo( Squad $squad )
    {
        // squad have no logo
        if( !$squad->getLogo() )
            return false;

        if( !$squad->getSquadLogoPaa() )
        {
            // logo provided but not converted
            $converted = $this->convert( ROOT_PATH . $squad->getSquadLogo() );

            if( ! $converted )
            {
                // convert failed return normal image
                return $squad->getSquadLogo();
            }
        }

        return $squad->getSquadLogoPaa();
    }


    public function convert($sourceImage)
    {
        Try {
            // convert to paa
            $command = 'cd '.escapeshellarg(dirname($sourceImage)).' && wine /var/www/racecore/library/TexView2/Pal2PacE.exe ' . escapeshellarg(basename($sourceImage)) . ' ' . escapeshellarg(basename($sourceImage, '.png') . '.paa');
            exec($command);

        } Catch( \Exception $e )
        {
            return false;
        }

        // check if paa exists
        if( file_exists(dirname($sourceImage) .'/'. basename( $sourceImage, '.png') . '.paa' ))
        {
            return true;
        }

        return true;
    }

    public function deleteLogo( $logo )
    {
        $logoPath = ROOT_PATH . '/uploads/logos/' . $logo . '/';

        if( !is_dir( $logoPath ) )
            return false;

        $it = new \RecursiveDirectoryIterator($logoPath, \RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new \RecursiveIteratorIterator($it,
            \RecursiveIteratorIterator::CHILD_FIRST);
        foreach($files as $file) {
            if ($file->getFilename() === '.' || $file->getFilename() === '..') {
                continue;
            }
            if ($file->isDir()){
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        rmdir($logoPath);



        return true;
    }

    public function saveLogo( $logoSpecs )
    {
        Try {
            $logoName =  md5(microtime(true) . uniqid(microtime(true)));
            $logoPath = ROOT_PATH . '/uploads/logos/' . $logoName . '/';

            mkdir($logoPath, 0777);
            chmod($logoPath, 0777);

            $image = new \Imagick( $logoSpecs['tmp_name'] );

            $saveLogoPath = $logoPath . $logoName . '.png';

            $image->setImageBackgroundColor('transparent');
            $image->stripimage();
            $image->setimageformat('png');
            $image->writeImage($saveLogoPath);
            $image->destroy();
            $image->clear();

            // convert to paa
            $this->convert($saveLogoPath);

            return $logoName;

        } catch ( \Exception $e )
        {
            return false;
        }

        return false;
    }

}