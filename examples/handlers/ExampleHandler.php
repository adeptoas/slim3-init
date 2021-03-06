<?php
	use Adepto\Slim3Init\HandlerCaller;

	use Adepto\Slim3Init\{
		Handlers\Handler,
		Handlers\Route,
		Exceptions\InvalidRequestException
	};

	use Psr\Http\Message\{
		ServerRequestInterface,
		ResponseInterface
	};

	class ExampleHandler extends Handler {

		/**
		 * getEcho
		 * 
		 * @param Psr\Http\Message\ServerRequestInterface   $request   Request
		 * @param Psr\Http\Message\ResponseInterface        $response  Response
		 * @param \stdClass                                 $args      Arguments
		 *
		 * @return Psr\Http\Message\ResponseInterface
		 */
		public function getEcho(ServerRequestInterface $request, ResponseInterface $response, \stdClass $args): ResponseInterface {
			return $response->withJSON($request->getQueryParams());
		}

		/**
		 * getNamedRoute
		 * 
		 * @param Psr\Http\Message\ServerRequestInterface   $request   Request
		 * @param Psr\Http\Message\ResponseInterface        $response  Response
		 * @param \stdClass                                 $args      Arguments
		 *
		 * @return Psr\Http\Message\ResponseInterface
		 */
		public function getNamedRoute(ServerRequestInterface $request, ResponseInterface $response, \stdClass $args): ResponseInterface {
			return $response->write($this->getPathFor('get-echo'));
		}

		/**
		 * postEcho
		 * 
		 * @param Psr\Http\Message\ServerRequestInterface   $request   Request
		 * @param Psr\Http\Message\ResponseInterface        $response  Response
		 * @param \stdClass                                 $args      Arguments
		 *
		 * @return Psr\Http\Message\ResponseInterface
		 */
		public function postEcho(ServerRequestInterface $request, ResponseInterface $response, \stdClass $args): ResponseInterface {
			return $response->withJSON($request->getParsedBody());
		}

		/**
		 * getException
		 * 
		 * @param Psr\Http\Message\ServerRequestInterface   $request   Request
		 * @param Psr\Http\Message\ResponseInterface        $response  Response
		 * @param \stdClass                                 $args      Arguments
		 *
		 * @return Psr\Http\Message\ResponseInterface
		 */
		public function getException(ServerRequestInterface $request, ResponseInterface $response, \stdClass $args): ResponseInterface {
			throw new InvalidRequestException('Halp!', 20);
		}

		/**
		 * getError
		 * 
		 * @param Psr\Http\Message\ServerRequestInterface   $request   Request
		 * @param Psr\Http\Message\ResponseInterface        $response  Response
		 * @param \stdClass                                 $args      Arguments
		 *
		 * @return Psr\Http\Message\ResponseInterface
		 */
		public function getError(ServerRequestInterface $request, ResponseInterface $response, \stdClass $args): ResponseInterface {
			throw new Error('Halp!', 20);
		}

		/**
		 * getCaller
		 * 
		 * @param Psr\Http\Message\ServerRequestInterface   $request   Request
		 * @param Psr\Http\Message\ResponseInterface        $response  Response
		 * @param \stdClass                                 $args      Arguments
		 *
		 * @return Psr\Http\Message\ResponseInterface
		 */
		public function getCaller(ServerRequestInterface $request, ResponseInterface $response, \stdClass $args): ResponseInterface {
			$caller = new HandlerCaller('/', ExampleHandler::class);

			return $response->withJSON($caller->get('/echo?calledBy=ExampleHandler'));
		}

		public static function getRoutes(): array {
			return [
				new Route('GET', '/echo', 'getEcho', ['addArg' => 'arg'], 'get-echo'),
				new Route('GET', '/named-route', 'getNamedRoute', [], 'get-named-route'),
				new Route('POST', '/echo', 'postEcho', [], 'post-echo'),
				new Route('GET', '/exception', 'getException'),
				new Route('GET', '/error', 'getError'),
				new Route('GET', '/caller', 'getCaller')
			];
		}
	}