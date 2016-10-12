<?php

namespace spec\Denismitr\Translit;

use Denismitr\Translit\Translit;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TranslitSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Translit::class);
    }

    function it_outputs_translited_slug_for_one_word()
    {
        $this->beConstructedWith('привет');

        $this->getSlug()->shouldBe('privet');
    }

    function it_outputs_translited_slug_for_several_inputs()
    {
        $this->beConstructedWith('завтра');

        $this->getSlug()->shouldBe('zavtra');

        $this->forString('сволочь');
        $this->getSlug()->shouldReturn('svoloch');

        $this->forString('cуперплохие');
        $this->getSlug()->shouldReturn('cuperplohie');

        $this->forString('жопа');
        $this->getSlug()->shouldReturn('zhopa');
    }

    function it_can_return_the_dictionary_that_it_uses()
    {
        $this->beConstructedWith('завтра');

        $this->getDictionary()->shouldBeArray();
    }

    function it_changes_whitespaces_for_dashes()
    {
        $this->forString('орел девятого легиона');
        $this->getSlug()->shouldReturn('orel-devyatogo-legiona');
    }

    function it_changes_specialcharecters_for_nothing()
    {
        $this->forString('СМИ узнали о планах России взимать плату за пересечение границы');
        $this->getSlug()->shouldReturn('smi-uznali-o-planah-rossii-vzimat-platu-za-peresechenie-granicy');

        $this->forString('Путин, Меркель и Олланд обсудили встречу в «нормандском формате»');
        $this->getSlug()->shouldReturn('putin-merkel-i-olland-obsudili-vstrechu-v-normandskom-formate');
    }
}
