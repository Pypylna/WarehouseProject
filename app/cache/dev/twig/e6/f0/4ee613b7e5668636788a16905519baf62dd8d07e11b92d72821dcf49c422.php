<?php

/* store/newstore.html.twig */
class __TwigTemplate_e6f04ee613b7e5668636788a16905519baf62dd8d07e11b92d72821dcf49c422 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "Dodaj nowy sklep do bazy danych<br><br>

";
        // line 3
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form_start');
        echo "
";
        // line 5
        echo "
";
        // line 9
        echo "
";
        // line 10
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'widget');
        echo "
";
        // line 12
        echo "
";
        // line 13
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form_end');
        echo "
";
        // line 15
        echo "
<button type=\"submit\">Dodaj!</button> ";
    }

    public function getTemplateName()
    {
        return "store/newstore.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  44 => 15,  40 => 13,  37 => 12,  33 => 10,  30 => 9,  27 => 5,  23 => 3,  19 => 1,);
    }
}
