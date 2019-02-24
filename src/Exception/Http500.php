<?php
namespace Exception;

class Http500 extends Http {
	const MESSAGE = 'Internal Server Error';
	const CODE = 500;
}

