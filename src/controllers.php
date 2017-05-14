<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

use Symfony\Component\Validator\Constraints as Assert;
//Request::setTrustedProxies(array('127.0.0.1'));

$app->get('/', function (Request $request) use ($app) {
    if (null === $user = $app['session']->get(null)) {
        return $app['twig']->render('index.html.twig', array('usuario'=>$app['session']->get('user'),
        'correo'=>$app['session']->get('email')));
    }
    if (null === $user = $app['session']->get('user')) {
        return $app->redirect($app['url_generator']->generate('login_vista'));
    }

})->bind('homepage');
//♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪

$app->get('/blog/{id}', function ($id) use ($app) {
    $sql = "SELECT * FROM clientes WHERE id_Cliente = $id";
    $post = $app['db']->fetchAssoc($sql, array((int) $id));
    
    return  $app['twig']->render('datos.html.twig', array(
        'id_Cliente'=>$post['id_Cliente'],
        'clave'=>$post['Clave'],
        'nombre'=>$post['Nombre'],
        'apellido'=>$post['Apellido'],
        'correo'=>$post['Correo'],
        'calle'=>$post['Calle'],
        'colonia'=>$post['Colonia'],
        'cp'=>$post['CP'],
        'ciudad'=>$post['Ciudad'],
        'pais'=>$post['Pais'],
        'telefono'=>$post['Telefono'],
        'fecha_creacion'=>$post['Fecha_creacion']
        ));

})->bind('blog_id');
//♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪

$app->get('/registro', function() use ($app) {
    $sql = 'SELECT * FROM Clientes';
    $post_cliente = $app['db']->fetchAll($sql, array());

    return $app['twig']->render('cliente.html.twig', array());
})->bind('registrar');
//♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪

$app->post('/registro', function(Request $request) use($app){
    $regcli = $app['db']->insert('Clientes', array(
        'id_Cliente' => $request->get('id_cliente'),
        'Clave' => $request->get('clave'),
        'Nombre' => $request->get('nombre'),
        'Apellido'=> $request->get('apellidos'),
        'Correo'=> $request->get('correo'),
        'Calle'=> $request->get('calle'),
        'Colonia'=> $request->get('colonia'),
        'CP'=> $request->get('cp'),
        'Ciudad'=> $request->get('ciudad'),
        'Pais'=> $request->get('pais'),
        'Telefono'=> $request->get('telefono')
    ));

    if( $regcli <= 0 )
    {
        $app['session']->setFlash('error', 'Se ha producido un error al insertar el elemento');
        
        return $app['db']->render('cliente.twig.html', array(
            'id_cliente' => $request->get('id_Cliente'),
            'clave' => $request->get('Clave'),
            'nombre' => $request->get('Nombre'),
            'apellidos'=> $request->get('Apellido'),
            'correo'=> $request->get('Correo'),
            'calle'=> $request->get('Calle'),
            'colonia'=> $request->get('Colonia'),
            'cp'=> $request->get('CP'),
            'ciudad'=> $request->get('Ciudad'),
            'pais'=> $request->get('Pais'),
            'telefono'=> $request->get('Telefono')));
    }
    else
    {
        // Investigar sobre session, setFlash
        $app['session']->setFlash('ok', 'Elemento insertado correctamente');
        
        return $app->redirect($app['url_generator']->generate('registrar'));
    }
})->bind('registrar_item');
//♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪

$app->get('/productos/', function (Request $request) use ($app) {
    if (null === $user = $app['session']->get('user')) {
        return $app->redirect($app['url_generator']->generate('login_vista'));
    }
    
    $sql = "SELECT sku, Categoria_id, Nombre_Producto, Precio, url_imagen_grande FROM Productos";
    $post_productos = $app['db']->fetchAll($sql, array());
    
    return  $app['twig']->render('productos.html.twig', array(
        'post_productos'=>$post_productos, 'usuario'=>$user, 'correo'=>$app['session']->get('email')
        ));
})->bind('productos_vista');
//♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪

$app->get('/productos/:sku/', function (Request $request) use ($app) {
    if (null === $user = $app['session']->get('user')) {
        return $app->redirect($app['url_generator']->generate('login_vista'));
    }

    $sql = "SELECT sku, Categoria_id, Nombre_Producto, Descripcion, Precio, url_imagen FROM Productos";
    $post_productos = $app['db']->fetchAll($sql, array());
    
    return  $app['twig']->render('productos_sku.html.twig', array(
        'post_productos'=>$post_productos
        ));
})->bind('productos_sku');
//♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪

$app->post('/agregar/sku', function(Request $request) use($app){
    //Falta pulir
    $regped = $app['db']->insert('Carrito', array(
        'sku' => $request->get('sku'),
        'Cantidad' => $request->get('cantidad'),
        'Cliente_id'=> $app['session']->get('user'))
    );
    $regpped = $app['db']->insert('Ppedidos', array(
        'sku' => $request->get('sku'),
        'Cantidad' => $request->get('cantidad'),
        'Cliente_id'=> $app['session']->get('user'))
    );
    
    $sqlpped="SELECT * FROM Ppedidos ORDER BY `idPedido` DESC LIMIT 1";
    $postpped = $app['db']->fetchAssoc($sqlpped);

    $regpc = $app['db']->insert('PedidosCarrito', array(
        'idCarrito'=> $app['session']->get('carrito'),
        'idPedido' => $postpped['idPedido']
        )
    );
    return "Pedido registrado  !";

})->bind('agregar_item');
//♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪

$app->get('/carrito', function(Request $request) use ($app){
    $user = $app['session']->get('user');
    $car = $app['session']->get('carrito');

    $sqlcheck = "SELECT * FROM Carritos
        INNER JOIN PedidosCarrito ON Carritos.idCarrito=$car and PedidosCarrito.idCarrito = $car 
        INNER JOIN Ppedidos On PedidosCarrito.idPedido=Ppedidos.idPedido 
        INNER JOIN Productos On Ppedidos.sku=Productos.sku";
    $post_check = $app['db']->fetchAll($sqlcheck, array());

    return $app['twig']->render('carrito.html.twig', array(
        'post_check'=>$post_check, 'usuario'=>$user, 'correo'=>$app['session']->get('email')));
})->bind('carrito_vista');
//♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪

$app->post('/carrito', function(Request $request) use ($app){
    $actped = $app['db']->update('Ppedidos', array(
        'Cantidad' => $request->get('cantidad')),
        array('idPedido'=>$request->get('idpedido'))
    );
    return "Registro actualizado!!!";
})->bind('carrito_actualizar');
//♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪

$app->get('/checkout', function(Request $request) use ($app){
    $car = $app['session']->get('carrito');

    $sqlcheck = "SELECT * FROM Carritos
        INNER JOIN PedidosCarrito ON Carritos.idCarrito=$car and PedidosCarrito.idCarrito = $car 
        INNER JOIN Ppedidos On PedidosCarrito.idPedido=Ppedidos.idPedido 
        INNER JOIN Productos On Ppedidos.sku=Productos.sku";
    $post_check = $app['db']->fetchAll($sqlcheck, array());

    $form = $app['form.factory']->createBuilder(FormType::class)
        ->add('Nombre_de_Tarjetahabiente', TextType::class, array(
            'constraints'=>array(new Assert\NotBlank(), new Assert\Length(array('max'=>255)))))
        ->add('Numero_de_Tarjeta_de_Credito', TextType::class, array(
            'constraints'=>array(new Assert\NotBlank(), new Assert\Length(array('max'=>16)))))
        ->add('CVC',  TextType::class, array(
            'constraints'=>array(new Assert\NotBlank(), new Assert\Length(array('max'=>255)))))
        ->add('fechaExpiracion', DateType::class)
        ->add('Ordenar', SubmitType::class, [
            'label' => 'Pagar'
            ])
        ->getForm();

    $form->handleRequest($request);

    if ($form->isValid()) {
        $data = $form->getData();

        // do something with the data
            return "programar vista para checkout";
        
        // redirect somewhere
        return $app->redirect($app['url_generator']->generate('carrito_vista'));
    }

    return $app['twig']->render('checkout.html.twig',array(
        'post_check'=>$post_check,
        'usuario'=>$app['session']->get('user'), 
        'correo'=>$app['session']->get('email'),
        'form' => $form->createView()
        ));
})->bind('checkout_vista');
//♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪

$app->post('/checkout', function(Request $request) use ($app){
    $regord = $app['db']->insert('Ordenes', array(
        'idCarrito'=>$app['session']->get('carrito'),
        'id_Cliente' => $app['session']->get('user'),
        'Numero_de_tarjeta' => $request->get('cantidad'),
        'Nombre_Tarjetahabiente' => $request->get('Nombre_de_Tarjetahabiente'),
        'Fecha_vencimiento' => $request->get('fechaExpiracion')
    ));
    return "Orden registrada.   Programar esta parte de la página";
})->bind('checkout_ordenar');
//♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪

/*$app->get('/productos/:sku', function (Request $request) use($app){
    return "Detalles: Programar detalles del producto!";

    $sku = $request->get('pp.sku');
    $sql ="SELECT * FROM Productos Where sku=?";
    $post = $app['db']->fetchAssoc($sql, array($sku));
})->bind('producto_detalle');*/

$app->match('/form', function (Request $request) use ($app) {
    // some default data for when the form is displayed the first time

    $form = $app['form.factory']->createBuilder(FormType::class)
        ->add('Correo', TextType::class, array(
            'constraints' => new Assert\Email()
        ))
        ->add('Clave', PasswordType::class, array(
            'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5)))
        ))

        ->add('billing_plan', ChoiceType::class, array(
            'choices' => array('Libre' => 1, 'Pequeño negocio' => 2, 'Corporativo' => 3),
            'expanded' => true,
            'constraints' => new Assert\Choice(array(1, 2, 3)),
        ))
        ->add('fechaVencimiento', DateType::class)
        ->add('submit', SubmitType::class, [
            'label' => 'Guardar',
        ])
        ->getForm();
    
    $form->handleRequest($request);

    if ($form->isValid()) {
        $data = $form->getData();

        // do something with the data
        $sql = "SELECT * FROM Clientes WHERE correo = ?";
        $post = $app['db']->fetchAssoc($sql, array(strtolower($data['correo'])));
    
        if ($post['Correo'] === $username && $post['Clave'] === $password) {
            //$app['session']->set('user', array('username' => $username));
            $app['session']->set('user', array('idc'=>$post['id_Cliente']));
            return $app->redirect($app['url_generator']->generate('productos_vista'));
        }
        // redirect somewhere
        return $app->redirect($app['url_generator']->generate('productos_sku'));
    }

    // display the form
    return $app['twig']->render('form.html.twig', array('form' => $form->createView()));
});
//♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪

$app->get('/login', function (Request $request) use ($app) {
    $username = $request->server->get('PHP_AUTH_USER', false);
    $password = $request->server->get('PHP_AUTH_PW');
 
    $sql = "SELECT * FROM Clientes WHERE correo = ?";
    $sqlcar="SELECT * FROM Carritos ORDER BY `idCarrito` DESC LIMIT 1";
    $post = $app['db']->fetchAssoc($sql, array(strtolower($username)));
    
    if ($post['Correo'] === $username && $post['Clave'] === $password) {
        //  $app['session']->set('user', array('username' => $username));
        $app['session']->set('user', $post['id_Cliente']);
        $app['session']->set('email', $post['Correo'] );
        $sqlcarrito="SELECT * FROM Carritos";
        $regped = $app['db']->insert('Carritos', array(
            'Cliente_id'=> $app['session']->get('user'))
        );
        
        if ($regped['idCarrito']==0){
            $sqlcount = $regped['idCarrito']+1;
            $postcar = $app['db']->fetchAssoc($sqlcar, array((int)$sqlcount));
        }else{
            $postcar = $app['db']->fetchAssoc($sqlcar, array((int)$sqlcount));
        }
        $app['session']->set('carrito', $postcar['idCarrito'] );
        return $app->redirect($app['url_generator']->generate('productos_vista'));
    }

    $response = new Response();
    $response->headers->set('WWW-Authenticate', sprintf('Basic realm="%s"', 'site_login'));
    $response->setStatusCode(401, 'Please sign in.');
    return $response;
    return "Welcome {$user['username']}!";
})->bind('login_vista');
//♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪♪

/*$app->get('/account', function () use ($app) {
    if (null === $user = $app['session']->get('user')) {
        return $app->redirect($app['url_generator']->generate('login_vista'));
    }

    return "Welcome {$user['username']}!";
});*/

$app->get('/logout', function () use ($app) {
    //session_unset();
    //session_destroy();
    //session_start();
    //$user = $app['session']->set(null,null);
    //$app['session.storage.handleRequest']=null;
    //$app['session']->session_destroy();
    //$app['session.storage.handler'] = null;
    //'invalidate_session'=>true;
    //return new Response('logout',200);
    $user = $app['session']->get(null);
    return $app->redirect($app['url_generator']->generate('homepage'));
})->bind('log_out');

$app->match('/formp', function (Request $request) use ($app) {
    // some default data for when the form is displayed the first time
    $sql = "SELECT sku, Categoria_id, Nombre_Producto, Descripcion, Precio, url_imagen FROM Productos";
    $post_productos = $app['db']->fetchAll($sql, array());

    $form = $app['form.factory']->createBuilder(FormType::class)
        //->add('SKU', TextType::class)
        //->add('Categoria', TextType::class)
        //->add('Producto', TextType::class)
        //->add('Descripcion', TextType::class)
        //->add('Precio', TextType::class)
        ->add('Cantidad', IntegerType::class)
        ->add('submit', SubmitType::class, [
            'label' => 'Agregar'])
        ->getForm();
    
    $form->handleRequest($request);

    if ($form->isValid() && $form->isSumitted()) {
        $data = $form->getData();

        // do something with the data
        if ($post['Correo'] === $username && $post['Clave'] === $password) {
            //$app['session']->set('user', array('username' => $username));
            $app['session']->set('user', array('idc'=>$post['id_Cliente']));
            return $app->redirect($app['url_generator']->generate('productos_vista'));
        }
        // redirect somewhere
        return $app->redirect($app['url_generator']->generate('productos_sku'));
    }

    // display the form
    return $app['twig']->render('prueba.html.twig', array('form' => $form->createView(), 'post_productos'=>$post_productos));
});

$app->get('/p', function(Request $request) use($app){
    $sql = "SELECT sku, Categoria_id, Nombre_Producto, Descripcion, Precio, url_imagen FROM Productos";
    $post_productos = $app['db']->fetchAll($sql, array());
    //$num_productos = $app['db']->count($sql, array());
    $skus=array();
    foreach ($post_productos as $sku) {
        # code...
        $skus[$sku['sku']]=$sku['Nombre'];
    }
    
    $form = $app['form.factory']->createBuilder(FormType::class)
    ->add('role','select', array(
        'selects'   => $skus,
        'expanded'  => true,)
    )->getForm();

    return $app['twig']->render('p.html.twig', array('form' => $form->createView(), 'skus'=>$skus));
    /*return  $app['twig']->render('productos_sku.html.twig', array(
        'post_productos'=>$post_productos
        ));*/
});

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html.twig',
        'errors/'.substr($code, 0, 2).'x.html.twig',
        'errors/'.substr($code, 0, 1).'xx.html.twig',
        'errors/default.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
