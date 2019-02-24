<?php
namespace Exception;

class Http405 extends Http {
	const MESSAGE = 'Method Not Allowed';
	const CODE = 405;
}

