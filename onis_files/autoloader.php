<?php

spl_autoload_register(function($strClassName){ include $strClassName . '.php'; });