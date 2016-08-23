<?php

namespace Onimla\HTML\Polymorphism;

interface UserInput {

    public function setDisabled();

    public function unsetDisabled();

    public function isDisabled();

    public function disabled();

    public function disable();

    public function enable();

    public function id($value = FALSE);

    public function isValueSet();

    public function name($value = FALSE);

    public function readOnly();

    public function isReadOnly();

    public function isNotReadOnly();

    public function setReadOnly();

    public function unsetReadOnly();

    public function required();

    public function isRequired();

    public function isNotRequired();

    public function setRequired();

    public function unsetRequired();

    public function type($value = FALSE);

    public function value($value = FALSE);
}
