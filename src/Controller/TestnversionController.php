<?php

namespace App\Controller;

use App\Entity\Testnversion;
use App\Form\Testnversion1Type;
use App\Repository\TestnversionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Api\External\Kinopoisk\GetContentInfoService;
use App\Service\Api\External\Kinopoisk\KinopoiskProcessorService;
use App\Service\Api\External\Convertio\ConvertioService;
use App\Service\Api\External\Smarty\SmartyContentApiService;
use App\Service\ImageHandler\ImageConverterService;
use Symfony\Component\HttpKernel\KernelInterface;
use App\Service\ThumbnailExtractorService;

#[Route('/testnversion')]
class TestnversionController extends AbstractController
{
    private $getContentInfo;
    private $imgConvertio;
    private $imgConvert;
    private $projectDir;
    private $imageHandler;
    private $kpProcessor;
    private $smartyApi;
    private $thumbnailExtractor;

    public function __construct(
                                    GetContentInfoService $getContentInfo, 
                                    ConvertioService $imgConvertio, 
                                    ImageConverterService $imgConvert,  
                                    KinopoiskProcessorService $kpProcessor, 
                                    SmartyContentApiService $smartyApi, 
                                    KernelInterface $kernel,
                                    ThumbnailExtractorService $thumbnailExtractor
                                    ){
        $this->getContentInfo = $getContentInfo;
        $this->imgConvertio = $imgConvertio;
        $this->imgConvert = $imgConvert;
        $this->kpProcessor = $kpProcessor;
        $this->smartyApi = $smartyApi;
        $this->projectDir = $kernel->getProjectDir();
        $this->thumbnailExtractor = $thumbnailExtractor;
    }


    #[Route('/', name: 'app_testnversion_index', methods: ['GET'])]
    public function index(TestnversionRepository $testnversionRepository): Response
    {
        

            // dump($this->smartyApi->modifyVideo($add['id'], ['poster_url' => 'http://mi-smarty.mycentra.ru/media/upload/tvmiddleware/posters/'.$add['id'].'/ps_poster.jpg']));
                // dump($this->smartyApi->modifyVideo($add['id'], ['genres' => '33', 'genres' => '12']));
            // dump($this->smartyApi->createVideo('testApi', 12, ['parent_control' => 1]));
            // dump($se_id = $this->smartyApi->createSeason('S01', 7505));
            // dump($ep_id = $this->smartyApi->createEpisode(7505, 'ep01', $se_id['id']));
            // dump($this->smartyApi->createVideoFile('test', 7505, ['episode_id' => $ep_id['id'], 'filename' => 'testFilename']));
            // dump($this->smartyApi->deleteVideo(7505));

        // dump($result->getContent());
        // dump(json_decode($result->getContent(), true));
        // dump($this->imgConvert->convertToJpg('https://avatars.mds.yandex.net/get-kinopoisk-image/10812607/580251ec-24c8-4af1-8ac2-943292805353/orig'));
        // dump($this->imgConvert->convertToJpg($this->projectDir . '/public/img/orig'));
        // dump($this->imageHandler->imageConvert('https://avatars.mds.yandex.net/get-kinopoisk-image/10812607/580251ec-24c8-4af1-8ac2-943292805353/orig', 'default'));
        // dump($this->imageHandler->imageConvert('https://avatars.mds.yandex.net/get-kinopoisk-image/10812607/580251ec-24c8-4af1-8ac2-943292805353/orig', 'convertio'));
        // dump($this->imgConvert->convertioToJpg('https://avatars.mds.yandex.net/get-kinopoisk-image/10812607/580251ec-24c8-4af1-8ac2-943292805353/orig'));
        return $this->render('testnversion/index.html.twig', [
            'testnversions' => $testnversionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_testnversion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $testnversion = new Testnversion();
        $form = $this->createForm(Testnversion1Type::class, $testnversion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($testnversion);
            $entityManager->flush();

            return $this->redirectToRoute('app_testnversion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('testnversion/new.html.twig', [
            'testnversion' => $testnversion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_testnversion_show', methods: ['GET'])]
    public function show(Testnversion $testnversion): Response
    {
        return $this->render('testnversion/show.html.twig', [
            'testnversion' => $testnversion,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_testnversion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Testnversion $testnversion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Testnversion1Type::class, $testnversion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_testnversion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('testnversion/edit.html.twig', [
            'testnversion' => $testnversion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_testnversion_delete', methods: ['POST'])]
    public function delete(Request $request, Testnversion $testnversion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$testnversion->getId(), $request->request->get('_token'))) {
            $entityManager->remove($testnversion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_testnversion_index', [], Response::HTTP_SEE_OTHER);
    }
}
