<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),

            new Anacona16\Bundle\ImageCropBundle\ImageCropBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new JMS\TranslationBundle\JMSTranslationBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Knp\Bundle\SnappyBundle\KnpSnappyBundle(),
            new Lexik\Bundle\FormFilterBundle\LexikFormFilterBundle(),
            new Liip\ImagineBundle\LiipImagineBundle(),
            new Petkopara\MultiSearchBundle\PetkoparaMultiSearchBundle(),
            new Petkopara\CrudGeneratorBundle\PetkoparaCrudGeneratorBundle(),
            new Presta\ImageBundle\PrestaImageBundle(),
            new Sg\DatatablesBundle\SgDatatablesBundle(),
            new Vich\UploaderBundle\VichUploaderBundle(),
            new CMEN\GoogleChartsBundle\CMENGoogleChartsBundle(),
            new Yectep\PhpSpreadsheetBundle\PhpSpreadsheetBundle(),
            new Liuggio\ExcelBundle\LiuggioExcelBundle(),
            new GGGGino\WordBundle\GGGGinoWordBundle(),
            new FOS\CKEditorBundle\FOSCKEditorBundle(),

            new DRI\SystemBundle\DRISystemBundle(),
            new DRI\ClientBundle\DRIClientBundle(),
            new DRI\PassportBundle\DRIPassportBundle(),
            new DRI\ExitBundle\DRIExitBundle(),
            new DRI\AgreementBundle\DRIAgreementBundle(),
            new DRI\UserBundle\DRIUserBundle(),
            new DRI\UsefulBundle\DRIUsefulBundle(),
            new DRI\DTBundle\DRIDTBundle(),
            new DRI\ForeingStudentBundle\DRIForeingStudentBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }
        /*
        if ('dev' === $this->getEnvironment()) {
            $bundles[] = new Symfony\Bundle\WebServerBundle\WebServerBundle();
        }*/

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
