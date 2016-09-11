<?php

namespace ElasticBundle\Controller;
use ElasticBundle\Service\Elastic\ElasticManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class OverviewController
 * @package ElasticBundle\Controller
 */
class OverviewController extends ExtendController
{
    public function indexAction(Request $request)
    {

        $elasticManager = $this->get('elastic.manager.elastic');
        $blocks = $elasticManager->getBlocks(null, null, true);

        return $this->render('ElasticBundle:Overview:index.html.twig',[
            'blocks' => $blocks,
        ]);
    }

    public function searchAction(Request $request)
    {

        $elasticManager = $this->get('elastic.manager.elastic');

        if($request->isMethod('post')) {

            $input = $request->get('input');

            if(!$input) {

                throw $this->createNotFoundException();

            }

            if(!preg_match('/^([a-zA-Z0-9-]{1,64})$/', $input)) {

                throw $this->createNotFoundException();

            }

            $inputType = $elasticManager->determineDataType($input);

            switch($inputType) {

                case ElasticManager::INPUT_DATA_TYPE_ADDRESS_RS: {

                    return $this->redirectToRoute('elastic_address',['address' => $input]);

                } break;
                case ElasticManager::INPUT_DATA_TYPE_BLOCK_HEIGHT: {

                    return $this->redirectToRoute('elastic_block',['block' => $input]);

                } break;
                case ElasticManager::INPUT_DATA_TYPE_TRANSACTION_FULL_HASH:
                case ElasticManager::INPUT_DATA_TYPE_TRANSACTION_ID: {

                    return $this->redirectToRoute('elastic_transaction',['transaction' => $input]);

                } break;
            }

        }


        throw $this->createNotFoundException();

    }
}
