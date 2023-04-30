<?php

namespace Interfaces;

interface GarplyIdAwareInterface {

	public function setGarplyId( int $garplyId ) : void;

	public function getGarplyId() : int;

}
