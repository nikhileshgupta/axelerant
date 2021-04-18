<?php

namespace Drupal\axelerant\Controller;

use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Process resource request.
 */
class PageContent {

  /**
   * Provides JSON of node.
   *
   * @return Symfony\Component\HttpFoundation\JsonResponse
   *   The JSON representation of node.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function get($api_key = NULL, $nid = NULL) {
    // Validate site api key.
    if ($api_key) {
      $key = \Drupal::configFactory()->get('system.site')->get('siteapikey');
      if ($api_key != $key) {
        throw new AccessDeniedHttpException();
      }
    }

    // Validate node type by nid.
    if ($nid) {
      $node = Node::load($nid);
      if (!is_object($node) || $node->getType() != 'page') {
        throw new AccessDeniedHttpException();
      }
    }

    // Serialize node object to Json.
    try {
      $data = \Drupal::service('serializer')->serialize($node, 'json', ['plugin_id' => 'entity']);
    }
    catch (\Exception $error) {
      return new JsonResponse($error->getMessage(), 400);
    }

    return JsonResponse::fromJsonString($data);
  }

}
