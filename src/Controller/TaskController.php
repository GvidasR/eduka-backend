<?php

namespace App\Controller;

use App\Entity\Task;
use App\Service\AnswersFormatter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/api/task", name="task_")
 */
class TaskController extends AbstractController
{
    /**
     * @Route("/", name="get_list", methods={"GET","HEAD"})
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function getList(SerializerInterface $serializer)
    {
        $list = $this->getDoctrine()->getRepository(Task::class)->findAll();
        $response = new Response($serializer->serialize($list, 'json'), 200, ['Content-Type' => 'application/json']);
        $response->headers->set("Access-Control-Allow-Origin", "*");
        return $response;
    }

    /**
     * @Route("/{id}", name="get_single", methods={"GET","HEAD"})
     * @param SerializerInterface $serializer
     * @param TranslatorInterface $translator
     * @param $id
     * @return Response
     */
    public function getSingle(SerializerInterface $serializer, TranslatorInterface $translator, $id)
    {
        $element = $this->getDoctrine()->getRepository(Task::class)->find($id);
        if ($element) {
            $response = new Response($serializer->serialize($element, 'json'), 200, ['Content-Type' => 'application/json']);
        } else {
            $response = new Response($serializer->serialize(["error" => $translator->trans('Not found')], 'json'), 404, ['Content-Type' => 'application/json']);
        }
        $response->headers->set("Access-Control-Allow-Origin", "*");
        return $response;
    }

    /**
     * @Route("/{id}", name="put_single", methods={"PUT","HEAD"})
     * @param SerializerInterface $serializer
     * @param TranslatorInterface $translator
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function editSingle(SerializerInterface $serializer, TranslatorInterface $translator, Request $request, $id)
    {
        $element = $this->getDoctrine()->getRepository(Task::class)->find($id);
        if ($element) {
            $data = json_decode($request->getContent());
            $element->setQuestion($data->question);
            $element->setAnswers(AnswersFormatter::fromObjectArray($data->answers));
            $this->getDoctrine()->getManager()->persist($element);
            $this->getDoctrine()->getManager()->flush();
            $response = new Response($serializer->serialize($element, 'json'), 200, ['Content-Type' => 'application/json']);
        } else {
            $response = new Response($serializer->serialize(["error" => $translator->trans('Not found')], 'json'), 404, ['Content-Type' => 'application/json']);
        }
        $response->headers->set("Access-Control-Allow-Origin", "*");
        return $response;
    }

    /**
     * @Route("/", name="post_single", methods={"POST","HEAD"})
     * @param SerializerInterface $serializer
     * @param Request $request
     * @return Response
     */
    public function newSingle(SerializerInterface $serializer, Request $request)
    {
        $data = json_decode($request->getContent());
        $element = new Task();
        $element->setQuestion($data->question);
        $this->getDoctrine()->getManager()->persist($element);
        $this->getDoctrine()->getManager()->flush();
        $response = new Response($serializer->serialize($element, 'json'), 201, ['Content-Type' => 'application/json']);
        $response->headers->set("Access-Control-Allow-Origin", "*");
        $response->headers->set("Access-Control-Allow-Headers", "content-type");
        return $response;
    }

    /**
     * @Route("/{id}", name="delete_single", methods={"DELETE","HEAD"})
     * @param SerializerInterface $serializer
     * @param TranslatorInterface $translator
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function deleteSingle(SerializerInterface $serializer, TranslatorInterface $translator, Request $request, $id)
    {
        $element = $this->getDoctrine()->getRepository(Task::class)->find($id);
        if ($element) {
            $this->getDoctrine()->getManager()->remove($element);
            $this->getDoctrine()->getManager()->flush();
            $response = new Response($serializer->serialize($element, 'json'), 200, ['Content-Type' => 'application/json']);
        } else {
            $response = new Response($serializer->serialize(["error" => $translator->trans('Not found')], 'json'), 404, ['Content-Type' => 'application/json']);
        }
        $response->headers->set("Access-Control-Allow-Origin", "*");
        return $response;
    }

    /**
     * @Route("/{id}", name="optionsWithId", methods={"OPTIONS"})
     * @param Request $request
     * @return Response
     */
    public function optionsWithId(Request $request,$id)
    {
        $response = new Response();
        $response->headers->set("Access-Control-Allow-Origin", "*");
        $response->headers->set("Access-Control-Allow-Headers", "content-type");
        $response->headers->set("Access-Control-Allow-Methods", "OPTIONS, HEAD, GET, PUT, DELETE");
        return $response;
    }

    /**
     * @Route("/", name="options", methods={"OPTIONS"})
     * @param Request $request
     * @return Response
     */
    public function options(Request $request)
    {
        $response = new Response();
        $response->headers->set("Access-Control-Allow-Origin", "*");
        $response->headers->set("Access-Control-Allow-Headers", "content-type");
        $response->headers->set("Access-Control-Allow-Methods", "OPTIONS, HEAD, GET, POST");
        return $response;
    }

}
