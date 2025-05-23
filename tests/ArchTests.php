<?php

arch()->expect('App')->not->toUse(['die', 'dd', 'dump']);

arch()->preset()->php();
arch()->preset()->laravel();
arch()->preset()->security();
