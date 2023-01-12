# Duster's Default Style Guide

## TLint

TLint performs both linting and fixing, although some issues cannot be fixed automatically.

Every formatter has a linter, but not every linter has a formatter.

### Formatters\ArrayParametersOverViewWith::class

Prefer view(..., [...]) over view(...)->with(...).

#### Examples

```diff
<?php

namespace App;

class Controller
{
    function index()
    {
-        return view('test.view')->with('testing', '1212');
+        return view('test.view', ['testing' => '1212']);
    }
}
```

### Formatters\FullyQualifiedFacades::class

Import facades using their full namespace.

#### Examples

```diff
<?php

namespace Test;

-use DB;
-use Storage;
+use Illuminate\Support\Facades\DB;
+use Illuminate\Support\Facades\Storage;
```

### Formatters\MailableMethodsInBuild::class

Mailable values (from and subject etc) should be set in build().

#### Examples

```diff
public function __construct($url)
{
    $this->url = $url;
-    $this->from('noreply@delivermyride.com', config('name'));
-    $this->subject(config('name') . ' Garage');
}

public function build()
{
+    $this->from('noreply@delivermyride.com', config('name'));
+    $this->subject(config('name') . ' Garage');
    return $this->view('auth.emails.email-login');
}
```

### Formatters\NoDatesPropertyOnModels::class

The $dates property was deprecated in Laravel 8. Use $casts instead.

#### Examples

```diff
<?php

class Post extends Model
{
-    protected $dates = ['email_verified_at'];
-
-    protected $casts = [];
+    protected $casts = [
+        'email_verified_at' => 'datetime',
+    ];
}
```

### Formatters\NoDocBlocksForMigrationUpDown::class

Remove doc blocks from the up and down method in migrations.

#### Examples

```diff
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuyRequestsTable extends Migration
{
-    /**
-     * Run the migrations.
-     *
-     * @return void
-     */
    public function up()
    {
        //
    }

-    /**
-     * Reverse the migrations.
-     *
-     * @return void
-     */
    public function down()
    {
        //
    }
}
```

### Formatters\NoLeadingSlashesOnRoutePaths::class

No leading slashes on route paths.

#### Examples

```diff
<?php

-Route::get('/home', function () {
+Route::get('home', function () {
    return '';
});
```

### Formatters\NoSpaceAfterBladeDirectives::class

No space between blade template directive names and the opening paren: `@section (` -> `@section(`.

#### Examples

```diff
-@section ('sidebar')
+@section('sidebar')
    This is the master sidebar.
@show

<div class="container">
-    @yield ('content')
+    @yield('content')
</div>

-<span @class (['p-4'])>Padding</span>
+<span @class(['p-4'])>Padding</span>

-<input type="checkbox" value="active" @checked (old('active', $user->active)) />
+<input type="checkbox" value="active" @checked(old('active', $user->active)) />

<select name="version">
-    @foreach ($product->versions as $version)
+    @foreach($product->versions as $version)
-        <option @selected (old('version') == $version)>
+        <option @selected(old('version') == $version)>
            {{ $version }}
        </option>
    @endforeach
</select>

@auth The user is authenticated @endauth

-@auth ('admin')
+@auth('admin')
    // The user is authenticated...
@endauth
```

### Formatters\OneLineBetweenClassVisibilityChanges::class

Class members of differing visibility must be separated by a blank line.

#### Examples

```diff
<?php

namespace App;

class Thing
{
    protected const OK = 1;
+
    private $ok;
}
```

### Formatters\RemoveLeadingSlashNamespaces::class

Prefer `Namespace\..`. over `\Namespace\...`.

#### Examples

```diff
<?php

-use \Tighten\TLint;
-use \PHPUnit\Framework\TestCase;
+use Tighten\TLint;
+use PHPUnit\Framework\TestCase;

echo test;
```

### Formatters\RequestHelperFunctionWherePossible::class

Use the request(...) helper function directly to access request values wherever possible.

#### Examples

```diff
<?php

namespace App;

class Controller
{
    public function index()
    {
-        return SavedVehicle::findOrFail(request()->get('savedVehicleId'));
+        return SavedVehicle::findOrFail(request('savedVehicleId'));
    }
}
```

### Formatters\RequestValidation::class

Use request()->validate(...) helper function or extract a FormRequest instead of using $this->validate(...) in controllers.

#### Examples

```diff
<?php

namespace App;

use App\Http\Controllers\Controller;

class ControllerA extends Controller
{
    public function store()
    {
-        $this->validate(['name' => 'required'], ['name.required' => 'Name is required']);
+        request()->validate(['name' => 'required'], ['name.required' => 'Name is required']);
    }
}
```

### Formatters\SpaceAfterBladeDirectives::class

Put a space between blade control structure names and the opening paren:`@if(` -> `@if (`

#### Examples

```diff
-@if(true)
+@if (true)
    This is true.
-@elseif(false)
+@elseif (false)
    This is false.
@endif

-@unless(true)
+@unless (true)
    This isn't true.
@endunless
```

### Formatters\SpacesAroundBladeRenderContent::class

Spaces around blade rendered content: `{{1 + 1}}` -> `{{ 1 + 1 }}`.

#### Examples

```diff
-{{1 + 1}}
+{{ 1 + 1 }}

-{{ 1 + 1 }} {{1 + 1}}
+{{ 1 + 1 }} {{ 1 + 1 }}

-{{1 + 1    }}
+{{ 1 + 1 }}
```

### Formatters\UseAuthHelperOverFacade::class

Prefer the auth() helper function over the Auth Facade.

#### Examples

```diff
@extends('layouts.app')
-@if (!\Illuminate\Support\Facades\Auth::check())
+-@if (!auth()->check())
    <label for="email">Email</label>
    <input id="email" class="form-control" type="email" name="email" required>
@else
    <input id="email" type="hidden" name="email" value="{{ auth()->user()->email }}" required>
@endif
```

### Linters\ApplyMiddlewareInRoutes::class

Apply middleware in routes (not controllers).

### Linters\NoJsonDirective::class

Use blade {{ $model }} auto escaping for models, and double quotes via json_encode over @json blade directive: <vue-comp :values='@json($var)'> -> <vue-comp :values="{{ $model }}"> OR <vue-comp :values="{!! json_encode($var) !!}">

### Linters\QualifiedNamesOnlyForClassName::class

Fully Qualified Class Names should only be used for accessing class names.

---

## PHP_CodeSniffer

PHP_CodeSniffer is primarily used to help enforce PSR1.

### PSR1.Files.SideEffects

PSR-1 section 2.3. A file SHOULD declare new symbols (classes, functions, constants, etc.) and cause no other side effects, or it SHOULD execute logic with side effects, but SHOULD NOT do both.

#### Configuration

Disable side effects for index file.

```xml
<exclude-pattern>/public/index.php</exclude-pattern>
```

### PSR1.Classes.ClassDeclaration

PSR-1 section 3. Namespaces and classes MUST follow PSR-0.

### PSR1.Classes.ClassDeclaration.MissingNamespace

Disable missing namespace rule for tests and database files.

#### Configuration

```xml
<exclude-pattern>*/database/*</exclude-pattern>
<exclude-pattern>*/tests/*</exclude-pattern>
```

### Squiz.Classes.ValidClassName

PSR-1 section 3. Class names MUST be declared in StudlyCaps.

### Generic.NamingConventions.UpperCaseConstantName

PSR-1 section 4.1. Class constants MUST be declared in all upper case with underscore separators.

### PSR1.Methods.CamelCapsMethodName

PSR-1 section 4.3. Method names MUST be declared in camelCase().

### PSR1.Methods.CamelCapsMethodName.NotCamelCaps

Disable camel caps rule for tests

#### Configuration

```xml
<exclude-pattern>*/tests/*</exclude-pattern>
```

### Generic.PHP.ForbiddenFunctions

No compact() and no 'dumps'.

#### Configuration

```xml
<properties>
    <property name="forbiddenFunctions" type="array">
        <element key="compact" value="null"/>
        <element key="dd" value="null"/>
        <element key="dump" value="null"/>
        <element key="var_dump" value="null"/>
        <element key="ray" value="null"/>
    </property>
</properties>
```

### Tighten.PHP.UseConfigOverEnv

Use config() over env().

### Configuration

```xml
<exclude-pattern>/config/*</exclude-pattern>
```

### Squiz.Classes.ClassFileName

Class name should match the file name.

---

## PHP CS Fixer

### Tighten/custom_controller_order

Orders controller class elements to match the order provided in the configuration.

##### Configuration

```php
[
  'order' => [
    'use_trait',
    'property_public_static',
    'property_protected_static',
    'property_private_static',
    'constant_public',
    'constant_protected',
    'constant_private',
    'property_public',
    'property_protected',
    'property_private',
    'construct',
    'method:__invoke',
    'method_public_static',
    'method_protected_static',
    'method_private_static',
    'method:index',
    'method:create',
    'method:store',
    'method:show',
    'method:edit',
    'method:update',
    'method:destroy',
    'method_public',
    'method_protected',
    'method_private',
    'magic',
  ],
]
```

### Tighten/custom_ordered_class_elements

Orders class elements to match the order provided in the configuration.

##### Configuration

```php
[
  'order' => [
    'use_trait',
    'property_public_static',
    'property_protected_static',
    'property_private_static',
    'constant_public',
    'constant_protected',
    'constant_private',
    'property_public',
    'property_protected',
    'property_private',
    'construct',
    'method:__invoke',
    'method_public_static',
    'method_protected_static',
    'method_private_static',
    'method_public',
    'method_protected',
    'method_private',
    'magic',
  ],
]
```

### Tighten/custom_phpunit_order

Orders PHPUnit fixtures at the top of files.

##### Configuration

```php
[
  'order' => [
    'use_trait',
    'property_public_static',
    'property_protected_static',
    'property_private_static',
    'constant_public',
    'constant_protected',
    'constant_private',
    'property_public',
    'property_protected',
    'property_private',
    'construct',
    'method:__invoke',
    'phpunit',
    'method_public_static',
    'method_protected_static',
    'method_private_static',
    'method_public',
    'method_protected',
    'method_private',
    'magic',
  ],
]
```

---

## Pint

Pint uses PHP CS Fixer under the hood to automatically fix issues using the following rules and configuration.

### array_indentation

Each element of an array must be indented exactly once.

##### Examples

```diff
<?php
 $foo = [
-   'bar' => [
-    'baz' => true,
-  ],
+    'bar' => [
+        'baz' => true,
+    ],
 ];
```

### array_syntax

PHP arrays should be declared using the configured syntax.

##### Configuration

```php
[
  'syntax' => 'short',
]
```

##### Examples

```diff
<?php
-array(1,2);
+[1,2];
```

### binary_operator_spaces

Binary operators should be surrounded by space as configured.

##### Configuration

```php
[
  'default' => 'single_space',
]
```

##### Examples

```diff
<?php
-$a= 1  + $b^ $d !==  $e or   $f;
+$a = 1 + $b ^ $d !== $e or $f;
```

### blank_line_after_namespace

There MUST be one blank line after the namespace declaration.

##### Examples

```diff
<?php
 namespace Sample\Sample;

-
 $a;
```

```diff
<?php
 namespace Sample\Sample;
+
 Class Test{}
```

### blank_line_after_opening_tag

Ensure there is no code on the same line as the PHP open tag and it is followed by a blank line.

##### Examples

```diff
-<?php $a = 1;
+<?php
+
+$a = 1;
 $b = 1;
```

### blank_line_before_statement

An empty line feed must precede any configured statement.

##### Configuration

```php
[
  'statements' => [
    0 => 'continue',
    1 => 'return',
  ],
]
```

##### Examples

```diff
foreach ($foo as $bar) {
     if ($bar->isTired()) {
         $bar->sleep();
+
         continue;
     }
 }
```

```diff
if (true) {
     $foo = $bar;
+
     return;
 }
```

### blank_line_between_import_groups

Putting blank lines between `use` statement groups.

##### Examples

```diff
<?php

 use function AAC;
+
 use const AAB;
+
 use AAA;
```

```diff
<?php
 use const AAAA;
 use const BBB;
+
 use Bar;
 use AAC;
 use Acme;
+
 use function CCC\AA;
 use function DDD;
```

```diff
<?php
 use const BBB;
 use const AAAA;
+
 use Acme;
 use AAC;
 use Bar;
+
 use function DDD;
 use function CCC\AA;
```

```diff
<?php
 use const AAAA;
 use const BBB;
+
 use Acme;
+
 use function DDD;
+
 use AAC;
+
 use function CCC\AA;
+
 use Bar;
```

### braces

The body of each structure MUST be enclosed by braces. Braces should be properly placed. Body of braces should be properly indented.

##### Configuration

```php
[
  'allow_single_line_anonymous_class_with_empty_body' => true,
  'allow_single_line_closure' => true,
  'position_after_control_structures' => 'same',
  'position_after_functions_and_oop_constructs' => 'next',
  'position_after_anonymous_constructs' => 'next',
]
```

##### Examples

```diff
<?php

-class Foo {
-    public function bar($baz) {
-        if ($baz = 900) echo "Hello!";
+class Foo
+{
+    public function bar($baz)
+    {
+        if ($baz = 900) {
+            echo "Hello!";
+        }

-        if ($baz = 9000)
+        if ($baz = 9000) {
             echo "Wait!";
+        }

-        if ($baz == true)
-        {
+        if ($baz == true) {
             echo "Why?";
-        }
-        else
-        {
+        } else {
             echo "Ha?";
         }

-        if (is_array($baz))
-            foreach ($baz as $b)
-            {
+        if (is_array($baz)) {
+            foreach ($baz as $b) {
                 echo $b;
             }
+        }
     }
 }
```

```diff
<?php
 $positive = function ($item) { return $item >= 0; };
 $negative = function ($item) {
-                return $item < 0; };
+    return $item < 0;
+};
```

```diff
<?php

-class Foo
-{
-    public function bar($baz)
-    {
-        if ($baz = 900) echo "Hello!";
+class Foo {
+    public function bar($baz) {
+        if ($baz = 900) {
+            echo "Hello!";
+        }

-        if ($baz = 9000)
+        if ($baz = 9000) {
             echo "Wait!";
+        }

-        if ($baz == true)
-        {
+        if ($baz == true) {
             echo "Why?";
-        }
-        else
-        {
+        } else {
             echo "Ha?";
         }

-        if (is_array($baz))
-            foreach ($baz as $b)
-            {
+        if (is_array($baz)) {
+            foreach ($baz as $b) {
                 echo $b;
             }
+        }
     }
 }
```

### curly_braces_position

Curly braces must be placed as configured.

##### Configuration

```php
[
  'control_structures_opening_brace' => 'same_line',
  'functions_opening_brace' => 'next_line_unless_newline_at_signature_end',
  'anonymous_functions_opening_brace' => 'same_line',
  'classes_opening_brace' => 'next_line_unless_newline_at_signature_end',
  'anonymous_classes_opening_brace' => 'next_line_unless_newline_at_signature_end',
  'allow_single_line_empty_anonymous_classes' => false,
  'allow_single_line_anonymous_functions' => false,
]
```

@TODO add more examples

##### Examples

```diff
<?php
// control_structures_opening_brace = same_line
-if (foo())
-{
+if (foo()) {
    bar();
}

// functions_opening_brace = next_line_unless_newline_at_signature_end
-function foo() {
+function foo()
+{
}

// anonymous_functions_opening_brace = same_line
-$foo = function()
-{
+$foo = function() {
};

// classes_opening_brace = next_line_unless_newline_at_signature_end
-class Foo {
+class Foo
+{
}

// anonymous_classes_opening_brace = next_line_unless_newline_at_signature_end
-$foo = new class {
+$foo = new class
+{
};

// allow_single_line_empty_anonymous_classes = false
-$bar = new class { private $baz; };
+$bar = new class
+{
+    private $baz;
+};

// allow_single_line_anonymous_functions = false
-$foo = function () { return true; };
+$foo = function () {
+    return true;
+};

```

### cast_spaces

A single space or none should be between cast and variable.

##### Examples

```diff
<?php
-$bar = ( string )  $a;
-$foo = (int)$b;
+$bar = (string) $a;
+$foo = (int) $b;
```

```diff
<?php
-$bar = ( string )  $a;
-$foo = (int)$b;
+$bar = (string) $a;
+$foo = (int) $b;
```

```diff
<?php
-$bar = ( string )  $a;
-$foo = (int) $b;
+$bar = (string)$a;
+$foo = (int)$b;
```

### class_attributes_separation

Class, trait and interface elements must be separated with one or none blank line.

##### Configuration

```php
[
  'elements' => [
    'method' => 'one',
  ],
]
```

##### Examples

```diff
final class Sample
     protected function foo()
     {
     }
+
     protected function bar()
     {
     }
-
-
 }
```

### class_definition

Whitespace around the keywords of a class, trait, enum or interfaces definition should be one space.

##### Configuration

```php
[
  'multi_line_extends_each_single_line' => true,
  'single_item_single_line' => true,
  'single_line' => true,
]
```

##### Examples

```diff
<?php

-class Foo
-extends Bar
-implements Baz, BarBaz
+class Foo extends Bar implements Baz, BarBaz
 {}
```

```diff
<?php

-class Foo
-extends Bar
-implements Baz
+class Foo extends Bar implements Baz
 {}
```

```diff
<?php

 interface Bar extends
-    Bar, BarBaz, FooBarBaz
+    Bar,
+    BarBaz,
+    FooBarBaz
 {}
```

### clean_namespace

Namespace must not contain spacing, comments or PHPDoc.

##### Examples

```diff
<?php
-namespace Foo \ Bar;
+namespace Foo\Bar;
```

```diff
<?php
-echo foo /* comment */ \ bar();
+echo foo\bar();
```

### compact_nullable_typehint

Remove extra spaces in a nullable typehint.

##### Examples

```diff
<?php
-function sample(? string $str): ? string
+function sample(?string $str): ?string
 {}
```

### concat_space

Concatenation should be spaced according configuration.

##### Configuration

```php
[
  'spacing' => 'one',
]
```

##### Examples

```diff
<?php
-$foo = 'bar' . 3 . 'baz'.'qux';
+$foo = 'bar' . 3 . 'baz' . 'qux';
```

### constant_case

The PHP constants `true`, `false`, and `null` MUST be written using the correct casing.

##### Configuration

```php
[
  'case' => 'lower',
]
```

##### Examples

```diff
<?php
-$a = FALSE;
-$b = True;
-$c = nuLL;
+$a = false;
+$b = true;
+$c = null;
```

### declare_equal_normalize

Equal sign in declare statement should be surrounded by spaces or not following configuration.

##### Examples

```diff
<?php
-declare(ticks =  1);
+declare(ticks=1);
```

```diff
<?php
-declare(ticks=1);
+declare(ticks = 1);
```

### elseif

The keyword `elseif` should be used instead of `else if` so that all control keywords look like single words.

##### Examples

```diff
<?php
 if ($a) {
-} else if ($b) {
+} elseif ($b) {
 }
```

### encoding

PHP code MUST use only UTF-8 without BOM (remove BOM).

##### Examples

```diff
-﻿<?php
+<?php

 echo "Hello!";
```

### explicit_string_variable

Converts implicit variables into explicit ones in double-quoted strings or heredoc syntax.

##### Examples

```diff
<?php
-$a = "My name is $name !";
-$b = "I live in $state->country !";
-$c = "I have $farm[0] chickens !";
+$a = "My name is {$name} !";
+$b = "I live in {$state->country} !";
+$c = "I have {$farm[0]} chickens !";
```

### full_opening_tag

PHP code must use the long `<?php` tags or short-echo `<?=` tags and not other tag variations.

##### Examples

```diff
-<?
+<?php

 echo "Hello!";
```

### fully_qualified_strict_types

Transforms imported FQCN parameters and return types in function arguments to short version.

##### Examples

```diff
use Foo\Bar;

 class SomeClass
 {
-    public function doSomething(\Foo\Bar $foo)
+    public function doSomething(Bar $foo)
     {
     }
 }
```

```diff
use Foo\Bar\Baz;

 class SomeClass
 {
-    public function doSomething(\Foo\Bar $foo): \Foo\Bar\Baz
+    public function doSomething(Bar $foo): Baz
     {
     }
 }
```

### function_declaration

Spaces should be properly placed in a function declaration.

##### Examples

```diff
class Foo
 {
-    public static function  bar   ( $baz , $foo )
+    public static function bar($baz , $foo)
     {
         return false;
     }
 }

-function  foo  ($bar, $baz)
+function foo($bar, $baz)
 {
     return false;
 }
```

```diff
<?php
-$f = function () {};
+$f = function() {};
```

```diff
<?php
-$f = fn () => null;
+$f = fn() => null;
```

### function_typehint_space

Ensure single space between function's argument and its typehint.

##### Examples

```diff
<?php
-function sample(array$a)
+function sample(array $a)
 {}
```

```diff
<?php
-function sample(array  $a)
+function sample(array $a)
 {}
```

### general_phpdoc_tag_rename

Renames PHPDoc tags.

##### Examples

```diff
<?php
 /**
- * @inheritDocs
- * {@inheritdocs}
+ * @inheritDoc
+ * {@inheritDoc}
  */
```

```diff
<?php
 /**
  * @inheritDocs
- * {@inheritdocs}
+ * {@inheritDoc}
  */
```

```diff
<?php
 /**
- * @inheritDocs
+ * @inheritDoc
  * {@inheritdocs}
  */
```

```diff
<?php
 /**
- * @inheritDocs
+ * @inheritDoc
  * {@inheritdocs}
  */
```

### global_namespace_import

Imports or fully qualifies global classes/functions/constants.

##### Configuration

```php
[
    'import_classes' => true,
    'import_constants' => true,
    'import_functions' => true,
]
```

##### Examples

```diff
<?php

 namespace Foo;
+use DateTimeImmutable;
+use function count;
+use const M_PI;

-if (\count($x)) {
-    /** @var \DateTimeImmutable $d */
-    $d = new \DateTimeImmutable();
-    $p = \M_PI;
+if (count($x)) {
+    /** @var DateTimeImmutable $d */
+    $d = new DateTimeImmutable();
+    $p = M_PI;
 }
```

### heredoc_to_nowdoc

Convert `heredoc` to `nowdoc` where possible.

##### Examples

```diff
<?php

-$a = <<<"TEST"
+$a = <<<'TEST'
 Foo
 TEST;
```

### include

Include/Require and file path should be divided with a single space. File path should not be placed under brackets.

##### Examples

```diff
<?php
-require ("sample1.php");
-require_once  "sample2.php";
-include       "sample3.php";
-include_once("sample4.php");
+require "sample1.php";
+require_once "sample2.php";
+include "sample3.php";
+include_once "sample4.php";
```

### increment_style

Pre- or post-increment and decrement operators should be used if possible.

##### Configuration

```php
[
  'style' => 'post',
]
```

##### Examples

```diff
<?php
-++$a;
---$b;
+$a++;
+$b--;
```

### indentation_type

Code MUST use configured indentation type.

##### Examples

```diff
<?php

 if (true) {
-	echo 'Hello!';
+    echo 'Hello!';
 }
```

### integer_literal_case

Integer literals must be in correct case.

##### Examples

```diff
<?php
-$foo = 0Xff;
-$bar = 0B11111111;
+$foo = 0xFF;
+$bar = 0b11111111;
```

### lambda_not_used_import

Lambda must not import variables it doesn't use.

##### Examples

```diff
<?php
-$foo = function() use ($bar) {};
+$foo = function() {};
```

### linebreak_after_opening_tag

Ensure there is no code on the same line as the PHP open tag.

##### Examples

```diff
-<?php $a = 1;
+<?php
+$a = 1;
 $b = 3;
```

### line_ending

All PHP files must use same line ending.

##### Examples

```diff

```

### list_syntax

List (`array` destructuring) assignment should be declared using the configured syntax. Requires PHP >= 7.1.

##### Examples

```diff
<?php
-list($sample) = $array;
+[$sample] = $array;
```

```diff
<?php
-[$sample] = $array;
+list($sample) = $array;
```

### lowercase_cast

Cast should be written in lower case.

##### Examples

```diff
<?php
-    $a = (BOOLEAN) $b;
-    $a = (BOOL) $b;
-    $a = (INTEGER) $b;
-    $a = (INT) $b;
-    $a = (DOUBLE) $b;
-    $a = (FLoaT) $b;
-    $a = (reaL) $b;
-    $a = (flOAT) $b;
-    $a = (sTRING) $b;
-    $a = (ARRAy) $b;
-    $a = (OBJect) $b;
-    $a = (UNset) $b;
-    $a = (Binary) $b;
+    $a = (boolean) $b;
+    $a = (bool) $b;
+    $a = (integer) $b;
+    $a = (int) $b;
+    $a = (double) $b;
+    $a = (float) $b;
+    $a = (real) $b;
+    $a = (float) $b;
+    $a = (string) $b;
+    $a = (array) $b;
+    $a = (object) $b;
+    $a = (unset) $b;
+    $a = (binary) $b;
```

```diff
<?php
-    $a = (BOOLEAN) $b;
-    $a = (BOOL) $b;
-    $a = (INTEGER) $b;
-    $a = (INT) $b;
-    $a = (DOUBLE) $b;
-    $a = (FLoaT) $b;
-    $a = (flOAT) $b;
-    $a = (sTRING) $b;
-    $a = (ARRAy) $b;
-    $a = (OBJect) $b;
-    $a = (UNset) $b;
-    $a = (Binary) $b;
+    $a = (boolean) $b;
+    $a = (bool) $b;
+    $a = (integer) $b;
+    $a = (int) $b;
+    $a = (double) $b;
+    $a = (float) $b;
+    $a = (float) $b;
+    $a = (string) $b;
+    $a = (array) $b;
+    $a = (object) $b;
+    $a = (unset) $b;
+    $a = (binary) $b;
```

### lowercase_keywords

PHP keywords MUST be in lower case.

##### Examples

```diff
<?php
-    FOREACH($a AS $B) {
-        TRY {
-            NEW $C($a, ISSET($B));
-            WHILE($B) {
-                INCLUDE "test.php";
+    foreach($a as $B) {
+        try {
+            new $C($a, isset($B));
+            while($B) {
+                include "test.php";
             }
-        } CATCH(\Exception $e) {
-            EXIT(1);
+        } catch(\Exception $e) {
+            exit(1);
         }
     }
```

### lowercase_static_reference

Class static references `self`, `static` and `parent` MUST be in lower case.

##### Examples

```diff
class Foo extends Bar
 {
     public function baz1()
     {
-        return STATIC::baz2();
+        return static::baz2();
     }

     public function baz2($x)
     {
-        return $x instanceof Self;
+        return $x instanceof self;
     }

-    public function baz3(PaRent $x)
+    public function baz3(parent $x)
     {
         return true;
     }
```

```diff
<?php
 class Foo extends Bar
 {
-    public function baz(?self $x) : SELF
+    public function baz(?self $x) : self
     {
         return false;
     }
```

### magic_method_casing

Magic method definitions and calls must be using the correct casing.

##### Examples

```diff
<?php
 class Foo
 {
-    public function __Sleep()
+    public function __sleep()
     {
     }
 }
```

```diff
<?php
-$foo->__INVOKE(1);
+$foo->__invoke(1);
```

### magic_constant_casing

Magic constants should be referred to using the correct casing.

##### Examples

```diff
<?php
-echo __dir__;
+echo __DIR__;
```

### method_argument_space

In method arguments and method call, there MUST NOT be a space before each comma and there MUST be one space after each comma. Argument lists MAY be split across multiple lines, where each subsequent line is indented once. When doing so, the first item in the list MUST be on the next line, and there MUST be only one argument per line.

##### Configuration

```php
[
  'on_multiline' => 'ignore',
]
```

##### Examples

```diff
<?php
-function sample($a=10,$b=20,$c=30) {}
-sample(1,  2);
+function sample($a=10, $b=20, $c=30) {}
+sample(1, 2);
```

### multiline_whitespace_before_semicolons

Forbid multi-line whitespace before the closing semicolon or move the semicolon to the new line for chained calls.

##### Configuration

```php
[
  'strategy' => 'no_multi_line',
]
```

##### Examples

```diff
<?php
 function foo () {
-    return 1 + 2
-        ;
+    return 1 + 2;
 }
```

### native_function_casing

Function defined by PHP should be called using the correct casing.

##### Examples

```diff
<?php
-STRLEN($str);
+strlen($str);
```

### native_function_type_declaration_casing

Native type hints for functions should use the correct case.

##### Examples

```diff
<?php
 class Bar {
-    public function Foo(CALLABLE $bar)
+    public function Foo(callable $bar)
     {
         return 1;
     }
```

```diff
<?php
-function Foo(INT $a): Bool
+function Foo(int $a): bool
 {
     return true;
 }
```

```diff
<?php
-function Foo(Iterable $a): VOID
+function Foo(iterable $a): void
 {
     echo 'Hello world';
 }
```

```diff
<?php
-function Foo(Object $a)
+function Foo(object $a)
 {
     return 'hi!';
 }
```

### new_with_braces

All instances created with `new` keyword must (not) be followed by braces.

##### Configuration

```php
[
  'named_class' => false,
  'anonymous_class' => false,
]
```

##### Examples

```diff
<?php

-$y = new class() {};
+$y = new class {};
```

```diff
<?php

-$x = new X();
+$x = new X;
```

### no_alias_functions

Master functions shall be used instead of aliases.

##### Examples

```diff
<?php
-$a = chop($b);
-close($b);
-$a = doubleval($b);
-$a = fputs($b, $c);
-$a = get_required_files();
-ini_alter($b, $c);
-$a = is_double($b);
-$a = is_integer($b);
-$a = is_long($b);
-$a = is_real($b);
-$a = is_writeable($b);
-$a = join($glue, $pieces);
-$a = key_exists($key, $array);
-magic_quotes_runtime($new_setting);
-$a = pos($array);
-$a = show_source($filename, true);
-$a = sizeof($b);
-$a = strchr($haystack, $needle);
-$a = imap_header($imap_stream, 1);
-user_error($message);
+$a = rtrim($b);
+closedir($b);
+$a = floatval($b);
+$a = fwrite($b, $c);
+$a = get_included_files();
+ini_set($b, $c);
+$a = is_float($b);
+$a = is_int($b);
+$a = is_int($b);
+$a = is_float($b);
+$a = is_writable($b);
+$a = implode($glue, $pieces);
+$a = array_key_exists($key, $array);
+set_magic_quotes_runtime($new_setting);
+$a = current($array);
+$a = highlight_file($filename, true);
+$a = count($b);
+$a = strstr($haystack, $needle);
+$a = imap_headerinfo($imap_stream, 1);
+trigger_error($message);
 mbereg_search_getregs();
```

```diff
<?php
 $a = is_double($b);
-mbereg_search_getregs();
+mb_ereg_search_getregs();
```

### no_alias_language_construct_call

Master language constructs shall be used instead of aliases.

##### Examples

```diff
<?php
-die;
+exit;
```

### no_alternative_syntax

Replace control structure alternative syntax to use braces.

##### Examples

```diff
<?php
-if(true):echo 't';else:echo 'f';endif;
+if(true) { echo 't';} else { echo 'f';}
```

```diff
-<?php if ($condition): ?>
+<?php if ($condition) { ?>
 Lorem ipsum.
-<?php endif; ?>
+<?php } ?>
```

### no_binary_string

There should not be a binary flag before strings.

##### Examples

```diff
-<?php $a = b'foo';
+<?php $a = 'foo';
```

```diff
-<?php $a = b<<<EOT
+<?php $a = <<<EOT
 foo
 EOT;
```

### no_blank_lines_after_class_opening

There should be no empty lines after class opening brace.

##### Examples

```diff
<?php
 final class Sample
 {
-
     protected function foo()
     {
     }
```

### no_blank_lines_after_phpdoc

There should not be blank lines between docblock and the documented element.

##### Examples

```diff
/**
  * This is the bar class.
  */
-
-
 class Bar {}
```

### no_closing_tag

The closing `?>` tag MUST be omitted from files containing only PHP.

##### Examples

```diff
class Sample
 {
 }
-?>
-
```

### no_empty_phpdoc

There should not be empty PHPDoc blocks.

##### Examples

```diff
-<?php /**  */
+<?php
```

### no_empty_statement

Remove useless (semicolon) statements.

##### Examples

```diff
-<?php $a = 1;;
+<?php $a = 1;
```

```diff
-<?php echo 1;2;
+<?php echo 1;
```

```diff
<?php while(foo()){
-    continue 1;
+    continue ;
 }
```

### no_extra_blank_lines

Removes extra blank lines and/or blank lines following configuration.

##### Configuration

```php
[
  'tokens' => [
    0 => 'extra',
    1 => 'throw',
    2 => 'use',
  ],
]
```

##### Examples

```diff
$foo = array("foo");

-
 $bar = "bar";
```

```diff
function foo($bar)
 {
     throw new \Exception("Hello!");
-
 }
```

```diff
namespace Foo;

 use Bar\Baz;
-
 use Baz\Bar;

 class Bar
```

### no_leading_import_slash

Remove leading slashes in `use` clauses.

##### Examples

```diff
<?php
 namespace Foo;
-use \Bar;
+use Bar;
```

### no_leading_namespace_whitespace

The namespace declaration line shouldn't contain leading whitespace.

##### Examples

```diff
<?php
- namespace Test8a;
-    namespace Test8b;
+namespace Test8a;
+namespace Test8b;
```

### no_mixed_echo_print

Either language construct `print` or `echo` should be used.

##### Configuration

```php
[
  'use' => 'echo',
]
```

##### Examples

```diff
-<?php print 'example';
+<?php echo 'example';
```

### no_multiline_whitespace_around_double_arrow

Operator `=>` should not be surrounded by multi-line whitespaces.

##### Examples

```diff
<?php
-$a = array(1
-
-=> 2);
+$a = array(1 => 2);
```

### no_short_bool_cast

Short cast `bool` using double exclamation mark should not be used.

##### Examples

```diff
<?php
-$a = !!$b;
+$a = (bool)$b;
```

### no_singleline_whitespace_before_semicolons

Single-line whitespace before closing semicolon are prohibited.

##### Examples

```diff
-<?php $this->foo() ;
+<?php $this->foo();
```

### no_spaces_after_function_name

When making a method or function call, there MUST NOT be a space between the method or function name and the opening parenthesis.

##### Examples

```diff
<?php
-require ('sample.php');
-echo (test (3));
-exit  (1);
-$func ();
+require('sample.php');
+echo(test(3));
+exit(1);
+$func();
```

### no_space_around_double_colon

There must be no space around double colons (also called Scope Resolution Operator or Paamayim Nekudotayim).

##### Examples

```diff
-<?php echo Foo\Bar :: class;
+<?php echo Foo\Bar::class;
```

### no_spaces_around_offset

There MUST NOT be spaces around offset braces.

##### Configuration

```php
[
  'positions' => [
    0 => 'inside',
    1 => 'outside',
  ],
]
```

##### Examples

```diff
<?php
-$sample = $b [ 'a' ] [ 'b' ];
+$sample = $b['a']['b'];
```

### no_spaces_inside_parenthesis

There MUST NOT be a space after the opening parenthesis. There MUST NOT be a space before the closing parenthesis.

##### Examples

```diff
<?php
-if ( $a ) {
-    foo( );
+if ($a) {
+    foo();
 }
```

```diff
<?php
-function foo( $bar, $baz )
+function foo($bar, $baz)
 {
 }
```

### no_trailing_whitespace

Remove trailing whitespace at the end of non-blank lines.

##### Examples

```diff
<?php
-$a = 1;
+$a = 1;
```

### no_trailing_whitespace_in_comment

There MUST be no trailing spaces inside comment or PHPDoc.

##### Examples

```diff
<?php
-// This is
-// a comment.
+// This is
+// a comment.
```

### no_unneeded_control_parentheses

Removes unneeded parentheses around control statements.

##### Configuration

```php
[
  'statements' => [
    0 => 'break',
    1 => 'clone',
    2 => 'continue',
    3 => 'echo_print',
    4 => 'return',
    5 => 'switch_case',
    6 => 'yield',
  ],
]
```

##### Examples

```diff
<?php
-while ($x) { while ($y) { break (2); } }
-clone($a);
-while ($y) { continue (2); }
-echo("foo");
-print("foo");
-return (1 + 2);
-switch ($a) { case($x); }
-yield(2);
+while ($x) { while ($y) { break 2; } }
+clone $a;
+while ($y) { continue 2; }
+echo "foo";
+print "foo";
+return 1 + 2;
+switch ($a) { case $x; }
+yield 2;
```

### no_unneeded_curly_braces

Removes unneeded curly braces that are superfluous and aren't part of a control structure's body.

##### Examples

```diff
-<?php {
+<?php
     echo 1;
-}
+

 switch ($b) {
-    case 1: {
+    case 1:
         break;
-    }
+
 }
```

```diff
<?php
-namespace Foo {
+namespace Foo;
     function Bar(){}
-}
+
```

### no_unset_cast

Variables must be set `null` instead of using `(unset)` casting.

##### Examples

```diff
<?php
-$a = (unset) $b;
+$a =  null;
```

### no_unused_imports

Unused `use` statements must be removed.

##### Examples

```diff
<?php
 use \DateTime;
-use \Exception;

 new DateTime();
```

### no_unreachable_default_argument_value

In function arguments there must not be arguments with default values before non-default ones.

##### Examples

```diff
<?php
-function example($foo = "two words", $bar) {}
+function example($foo, $bar) {}
```

### no_useless_return

There should not be an empty `return` statement at the end of a function.

##### Examples

```diff
function example($b) {
     if ($b) {
         return;
     }
-    return;
+
 }
```

### no_whitespace_before_comma_in_array

In array declaration, there MUST NOT be a whitespace before each comma.

##### Examples

```diff
-<?php $x = array(1 , "2");
+<?php $x = array(1, "2");
```

```diff
<?php
     $x = [<<<EOD
 foo
-EOD
-        , 'bar'
+EOD, 'bar'
     ];
```

### no_whitespace_in_blank_line

Remove trailing whitespace at the end of blank lines.

##### Examples

```diff
<?php
-
+
 $a = 1;
```

### normalize_index_brace

Array index should always be written by using square braces.

##### Examples

```diff
<?php
-echo $sample{$index};
+echo $sample[$index];
```

### not_operator_with_successor_space

Logical NOT operators (`!`) should have one trailing whitespace.

##### Examples

```diff
<?php

-if (!$bar) {
+if (! $bar) {
     echo "Help!";
 }
```

### object_operator_without_whitespace

There should not be space before or after object operators `->` and `?->`.

##### Examples

```diff
-<?php $a  ->  b;
+<?php $a->b;
```

### ordered_imports

Ordering `use` statements.

##### Configuration

```php
[
  'sort_algorithm' => 'alpha',
  'imports_order' => [
    0 => 'const',
    1 => 'class',
    2 => 'function',
  ],
]
```

##### Examples

```diff
<?php
-use const BBB;
 use const AAAA;
+use const BBB;

-use Acme;
 use AAC;
+use Acme;
 use Bar;

-use function DDD;
 use function CCC\AA;
+use function DDD;
```

### psr_autoloading

Classes must be in a path that matches their namespace, be at least one namespace deep and the class name should match the file name.

Pint explicitly sets this to `false`.

### phpdoc_indent

Docblocks should have the same indentation as the documented subject.

##### Examples

```diff
<?php
 class DocBlocks
 {
-/**
- * Test constants
- */
+    /**
+     * Test constants
+     */
     const INDENT = 1;
 }
```

### phpdoc_inline_tag_normalizer

Fixes PHPDoc inline tags.

##### Examples

```diff
<?php
 /**
- * @{TUTORIAL}
- * {{ @link }}
+ * {@TUTORIAL}
+ * {@link}
  * @inheritDoc
  */
```

```diff
<?php
 /**
- * @{TUTORIAL}
+ * {@TUTORIAL}
  * {{ @link }}
  * @inheritDoc
  */
```

### phpdoc_no_access

`@access` annotations should be omitted from PHPDoc.

##### Examples

```diff
class Foo
 {
     /**
      * @internal
-     * @access private
      */
     private $bar;
 }
```

### phpdoc_no_package

`@package` and `@subpackage` annotations should be omitted from PHPDoc.

##### Examples

```diff
<?php
 /**
  * @internal
- * @package Foo
- * subpackage Bar
  */
 class Baz
 {
```

### phpdoc_no_useless_inheritdoc

Classy that does not inherit must not have `@inheritdoc` tags.

##### Examples

```diff
<?php
-/** {@inheritdoc} */
+/** */
 class Sample
 {
 }
```

```diff
class Sample
 {
     /**
-     * @inheritdoc
+     *
      */
     public function Test()
     {
```

### phpdoc_order

Annotations in PHPDoc should be ordered so that `@param` annotations come first, then `@throws` annotations, then `@return` annotations.

##### Configuration

```php
[
  'order' => [
    0 => 'param',
    1 => 'return',
    2 => 'throws',
  ],
]
```

##### Examples

```diff
/**
  * Hello there!
  *
- * @throws Exception|RuntimeException foo
  * @custom Test!
- * @return int  Return the number of changes.
  * @param string $foo
  * @param bool   $bar Bar
+ * @throws Exception|RuntimeException foo
+ * @return int  Return the number of changes.
  */
```

### phpdoc_scalar

Scalar types should always be written in the same form. `int` not `integer`, `bool` not `boolean`, `float` not `real` or `double`.

##### Examples

```diff
<?php
 /**
- * @param integer $a
- * @param boolean $b
- * @param real $c
+ * @param int $a
+ * @param bool $b
+ * @param float $c
  *
- * @return double
+ * @return float
  */
 function sample($a, $b, $c)
 {
```

```diff
<?php
 /**
  * @param integer $a
- * @param boolean $b
+ * @param bool $b
  * @param real $c
  */
 function sample($a, $b, $c)
```

### phpdoc_separation

Annotations in PHPDoc should be grouped together so that annotations of the same type immediately follow each other, and annotations of a different type are separated by a single blank line.

##### Configuration

```php
[
  'groups' => [
    0 =>   [
      0 => 'deprecated',
      1 => 'link',
      2 => 'see',
      3 => 'since',
    ],
    1 =>   [
      0 => 'author',
      1 => 'copyright',
      2 => 'license',
    ],
    2 =>   [
      0 => 'category',
      1 => 'package',
      2 => 'subpackage',
    ],
    3 =>   [
      0 => 'property',
      1 => 'property-read',
      2 => 'property-write',
    ],
    4 =>   [
      0 => 'param',
      1 => 'return',
    ],
  ],
]
```

##### Examples

```diff
<?php
 /**
  * Description.
- * @param string $foo
- *
  *
+ * @param string $foo
  * @param bool   $bar Bar
+ *
  * @throws Exception|RuntimeException
+ *
  * @return bool
  */
 function fnc($foo, $bar) {}
```

### phpdoc_single_line_var_spacing

Single line `@var` PHPDoc should have proper spacing.

##### Examples

```diff
-<?php /**@var   MyClass   $a   */
+<?php /** @var MyClass $a */
 $a = test();
```

### phpdoc_summary

PHPDoc summary should end in either a full stop, exclamation mark, or question mark.

##### Examples

```diff
<?php
 /**
- * Foo function is great
+ * Foo function is great.
  */
 function foo () {}
```

### phpdoc_to_comment

Docblocks should only be used on structural elements.

Pint explicitly sets this to `false`.

### phpdoc_tag_type

Forces PHPDoc tags to be either regular annotations or inline.

##### Configuration

```php
[
  'tags' => [
    'inheritdoc' => 'inline',
  ],
]
```

##### Examples

```diff
<?php
 /**
- * @inheritdoc
+ * {@inheritdoc}
  */
```

### phpdoc_trim

PHPDoc should start and end with content, excluding the very first and last line of the docblocks.

##### Examples

```diff
<?php
 /**
- *
  * Foo must be final class.
- *
- *
  */
 final class Foo {}
```

### phpdoc_types

The correct case must be used for standard PHP types in PHPDoc.

##### Examples

```diff
<?php
 /**
- * @param STRING|String[] $bar
+ * @param string|string[] $bar
  *
- * @return inT[]
+ * @return int[]
  */
```

```diff
<?php
 /**
- * @param BOOL $foo
+ * @param bool $foo
  *
  * @return MIXED
  */
```

### php_unit_test_annotation

Adds or removes @test annotations from tests, following configuration.

##### Configuration

```php
[
  'style' => 'annotation',
]
```

##### Examples

```diff
<?php
 class Test extends \PhpUnit\FrameWork\TestCase
 {
-public function testItDoesSomething() {}}
+/**
+ * @test
+ */
+public function itDoesSomething() {}}
```

### phpdoc_var_without_name

`@var` and `@type` annotations of classy properties should not contain the name.

##### Examples

```diff
final class Foo
 {
     /**
-     * @var int $bar
+     * @var int
      */
     public $bar;

     /**
-     * @type $baz float
+     * @type float
      */
     public $baz;
 }
```

### return_type_declaration

Adjust spacing around colon in return type declarations and backed enum types.

##### Configuration

```php
[
  'space_before' => 'none',
]
```

##### Examples

```diff
<?php
-function foo(int $a):string {};
+function foo(int $a): string {};
```

### self_accessor

Inside class or interface element `self` should be preferred to the class name itself.

##### Examples

```diff
class Sample
 {
     const BAZ = 1;
-    const BAR = Sample::BAZ;
+    const BAR = self::BAZ;

     public function getBar()
     {
-        return Sample::BAR;
+        return self::BAR;
     }
 }
```

### short_scalar_cast

Cast `(boolean)` and `(integer)` should be written as `(bool)` and `(int)`, `(double)` and `(real)` as `(float)`, `(binary)` as `(string)`.

##### Examples

```diff
<?php
-$a = (boolean) $b;
-$a = (integer) $b;
-$a = (double) $b;
-$a = (real) $b;
+$a = (bool) $b;
+$a = (int) $b;
+$a = (float) $b;
+$a = (float) $b;

-$a = (binary) $b;
+$a = (string) $b;
```

```diff
<?php
-$a = (boolean) $b;
-$a = (integer) $b;
-$a = (double) $b;
+$a = (bool) $b;
+$a = (int) $b;
+$a = (float) $b;

-$a = (binary) $b;
+$a = (string) $b;
```

### simple_to_complex_string_variable

Converts explicit variables in double-quoted strings and heredoc syntax from simple to complex format (`${` to `{$`).

##### Examples

```diff
<?php
 $name = 'World';
-echo "Hello ${name}!";
+echo "Hello {$name}!";
```

```diff
<?php
 $name = 'World';
 echo <<<TEST
-Hello ${name}!
+Hello {$name}!
 TEST;
```

### simplified_null_return

A return statement wishing to return `void` should not return `null`.

Pint explicitly sets this to `false`.

### single_blank_line_at_eof

A PHP file without end tag must always end with a single empty line feed.

##### Examples

```diff
<?php
 $a = 1;
+
```

```diff
<?php
 $a = 1;

-
```

### single_blank_line_before_namespace

There should be exactly one blank line before a namespace declaration.

##### Examples

```diff
-<?php  namespace A {}
+<?php
+
+namespace A {}
```

```diff
<?php

-
 namespace A{}
```

### single_class_element_per_statement

There MUST NOT be more than one property or constant declared per statement.

##### Configuration

```php
[
  'elements' => [
    0 => 'const',
    1 => 'property',
  ],
]
```

##### Examples

```diff
<?php
 final class Example
 {
-    const FOO_1 = 1, FOO_2 = 2;
-    private static $bar1 = array(1,2,3), $bar2 = [1,2,3];
+    const FOO_1 = 1;
+    const FOO_2 = 2;
+    private static $bar1 = array(1,2,3);
+    private static $bar2 = [1,2,3];
 }
```

### single_import_per_statement

There MUST be one use keyword per declaration.

##### Examples

```diff
<?php
-use Foo, Sample, Sample\Sample as Sample2;
+use Foo;
+use Sample;
+use Sample\Sample as Sample2;
```

```diff
<?php
-use Space\Models\ {
-    TestModelA,
-    TestModelB,
-    TestModel,
-};
+use Space\Models\TestModelA;
+use Space\Models\TestModelB;
+use Space\Models\TestModel;
```

### single_line_after_imports

Each namespace use MUST go on its own line and there MUST be one blank line after the use statements block.

##### Examples

```diff
namespace Foo;

 use Bar;
 use Baz;
+
 final class Example
 {
 }
```

```diff
namespace Foo;
 use Bar;
 use Baz;

-
 final class Example
 {
 }
```

### single_line_comment_style

Single-line comments and multi-line comments with only one line of actual content should use the `//` syntax.

##### Configuration

```php
[
  'comment_types' => [
    0 => 'hash',
  ],
]
```

##### Examples

```diff
-<?php # comment
+<?php // comment
```

### single_quote

Convert double quotes to single quotes for simple strings.

##### Examples

```diff
<?php

-$a = "sample";
+$a = 'sample';
 $b = "sample with 'single-quotes'";
```

```diff
<?php

-$a = "sample";
-$b = "sample with 'single-quotes'";
+$a = 'sample';
+$b = 'sample with \'single-quotes\'';
```

### space_after_semicolon

Fix whitespace after a semicolon.

##### Examples

```diff
<?php
-                        sample();     $test = 1;
-                        sample();$test = 2;
-                        for ( ;;++$sample) {
+                        sample(); $test = 1;
+                        sample(); $test = 2;
+                        for ( ; ; ++$sample) {
                         }
```

```diff
<?php
-for ($i = 0; ; ++$i) {
+for ($i = 0;; ++$i) {
 }
```

### standardize_not_equals

Replace all `<>` with `!=`.

##### Examples

```diff
<?php
-$a = $b <> $c;
+$a = $b != $c;
```

### statement_indentation

Each statement must be indented.

##### Examples

```diff
<?php
 if ($baz == true) {
-  echo "foo";
+    echo "foo";
 }
 else {
-      echo "bar";
+    echo "bar";
 }
```

### switch_case_semicolon_to_colon

A case should be followed by a colon and not a semicolon.

##### Examples

```diff
<?php
     switch ($a) {
-        case 1;
+        case 1:
             break;
-        default;
+        default:
             break;
     }
```

### switch_case_space

Removes extra spaces between colon and case value.

##### Examples

```diff
<?php
     switch($a) {
-        case 1   :
+        case 1:
             break;
-        default     :
+        default:
             return 2;
     }
```

### ternary_operator_spaces

Standardize spaces around ternary operator.

##### Examples

```diff
-<?php $a = $a   ?1 :0;
+<?php $a = $a ? 1 : 0;
```

### trailing_comma_in_multiline

Multi-line arrays, arguments list, parameters list and `match` expressions must have a trailing comma.

##### Configuration

```php
[
  'elements' => [
    0 => 'arrays',
  ],
]
```

##### Examples

```diff
<?php
 array(
     1,
-    2
+    2,
 );
```

### trim_array_spaces

Arrays should be formatted like function/method arguments, without leading or trailing single line space.

##### Examples

```diff
<?php
-$sample = array( );
-$sample = array( 'a', 'b' );
+$sample = array();
+$sample = array('a', 'b');
```

### types_spaces

A single space or none should be around union type and intersection type operators.

##### Examples

```diff
try
 {
     new Foo();
-} catch (ErrorA | ErrorB $e) {
+} catch (ErrorA|ErrorB $e) {
 echo'error';}
```

```diff
try
 {
     new Foo();
-} catch (ErrorA|ErrorB $e) {
+} catch (ErrorA | ErrorB $e) {
 echo'error';}
```

```diff
<?php
-function foo(int | string $x)
+function foo(int|string $x)
 {
 }
```

### unary_operator_spaces

Unary operators should be placed adjacent to their operands.

##### Examples

```diff
<?php
-$sample ++;
--- $sample;
-$sample = ! ! $a;
-$sample = ~  $c;
-function & foo(){}
+$sample++;
+--$sample;
+$sample = !!$a;
+$sample = ~$c;
+function &foo(){}
```

### visibility_required

Visibility MUST be declared on all properties and methods; `abstract` and `final` MUST be declared before the visibility; `static` MUST be declared after the visibility.

##### Configuration

```php
[
  'elements' => [
    0 => 'method',
    1 => 'property',
  ],
]
```

##### Examples

```diff
<?php
 class Sample
 {
-    var $a;
-    static protected $var_foo2;
+    public $a;
+    protected static $var_foo2;

-    function A()
+    public function A()
     {
     }
 }
```

### whitespace_after_comma_in_array

In array declaration, there MUST be a whitespace after each comma.

##### Examples

```diff
<?php
-$sample = array(1,'a',$b,);
+$sample = array(1, 'a', $b, );
```
