<?php
namespace Exception;

class Http400 extends Http {
	const MESSAGE = 'Method Not Allowed';
	const CODE = 405;
}

