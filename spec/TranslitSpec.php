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
        $this->transform('привет')->shouldBe('privet');
    }

    function it_outputs_translited_slug_for_two_words()
    {
        $this->transform('Эхо москвы')->shouldBe('ehkho-moskvy');
    }

    function it_outputs_translited_slug_for_several_inputs()
    {
        $this->transform('завтра')->shouldBe('zavtra');
        $this->transform('сволочь')->shouldReturn('svoloch');
        $this->transform('cуперплохие')->shouldReturn('cuperplohie');
        $this->transform('жопа')->shouldReturn('zhopa');
    }

    function it_changes_whitespaces_for_dashes()
    {
        $this->transform('орел девятого легиона')->shouldReturn('orel-devyatogo-legiona');
    }

    function it_trims_the_input_string()
    {
        $this
            ->transform(' Ведущие сайты объявлений! Точный подбор для любой тематики! Эффективно! ')
            ->shouldReturn('vedushchie-sajty-obyavlenij-tochnyj-podbor-dlya-lyuboj-tematiki-ehffektivno')
        ;
    }

    function it_can_deal_with_h_kh_complexities()
    {
        $this
            ->transform('сходил тухачевский под пихту в тюхал свой хер в хуйню')
            ->shouldReturn('skhodil-tuhachevskij-pod-pihtu-v-tyuhal-svoj-her-v-hujnyu')
        ;
    }

    function it_forces_lowercase_on_all_data()
    {
        $this
            ->transform('Россия завершила поставку зенитных ракетных систем С-300 в Иран')
            ->shouldBe('rossiya-zavershila-postavku-zenitnyh-raketnyh-sistem-s-300-v-iran')
        ;

        $this
            ->transform('Шакро Молодого назвали лидером преступного сообщества России')
            ->shouldReturn('shakro-molodogo-nazvali-liderom-prestupnogo-soobshchestva-rossii')
        ;

        $this
            ->transform('В России испытали поражающее ЦЕЛЬ без СНАаряДов оружие')
            ->shouldReturn('v-rossii-ispytali-porazhayushchee-cel-bez-snaaryadov-oruzhie')
        ;
    }

    function it_changes_specialcharacters_for_nothing()
    {
        $this
            ->transform("СМИ узнали о планах России `взимать` 'плату' ~за \пересечение /границы")
            ->shouldReturn('smi-uznali-o-planah-rossii-vzimat-platu-za-peresechenie-granicy')
        ;

        $this
            ->transform("Строка для транслитерации, по правилам Яндекса!")
            ->shouldReturn('stroka-dlya-transliteracii-po-pravilam-yandeksa')
        ;

        $this
            ->transform('Путин, Меркель и /О/лланд :!обсудили? встречу в «нормандском формате»')
            ->shouldReturn('putin-merkel-i-olland-obsudili-vstrechu-v-normandskom-formate')
        ;
    }


    function it_translits_long_frases()
    {
        $this
            ->transform("Лауреатом Нобелевской премии по литературе в 2016 г. стал американский музыкант и автор-исполнитель Боб Дилан. Он получил награду «за создание новых поэтических выражений в рамках американской песенной традиции».")
            ->shouldReturn("laureatom-nobelevskoj-premii-po-literature-v-2016-g-stal-amerikanskij-muzykant-i-avtor-ispolnitel-bob-dilan-on-poluchil-nagradu-za-sozdanie-novyh-poehticheskih-vyrazhenij-v-ramkah-amerikanskoj-pesennoj-tradicii")
        ;

        $this
            ->transform("Пресс-секретарь президента России Дмитрий Песков рассказал, что Владимиру Путину сообщили о публикациях в СМИ о нападении на дочь бойца смешанных стилей Федора Емельяненко. Российская газета 13:50")
            ->shouldReturn('press-sekretar-prezidenta-rossii-dmitrij-peskov-rasskazal-chto-vladimiru-putinu-soobshchili-o-publikaciyah-v-smi-o-napadenii-na-doch-bojca-smeshannyh-stilej-fedora-emelyanenko-rossijskaya-gazeta-13-50')
        ;

        $this
            ->transform("В сервисе есть мощный инструмент для создания списков подписчиков как по простым, так и по невероятно сложным сценариям. Например, можно легко выделить тех, кто не открыл ни одно ваше письмо или тех, кто ни разу не кликнул по ссылкам. Можно найти всех подписчиков на яндексе и выделить их в отдельный список. В общем, в зависимости от того, какие данные о подписчике вы собираете, можно формировать очень точные выборки и сегменты подписчиков. И делается это все очень просто и быстро.")
            ->shouldReturn('v-servise-est-moshchnyj-instrument-dlya-sozdaniya-spiskov-podpischikov-kak-po-prostym-tak-i-po-neveroyatno-slozhnym-scenariyam-naprimer-mozhno-legko-vydelit-tekh-kto-ne-otkryl-ni-odno-vashe-pismo-ili-tekh-kto-ni-razu-ne-kliknul-po-ssylkam-mozhno-najti-vsekh-podpischikov-na-yandekse-i-vydelit-ih-v-otdelnyj-spisok-v-obshchem-v-zavisimosti-ot-togo-kakie-dannye-o-podpischike-vy-sobiraete-mozhno-formirovat-ochen-tochnye-vyborki-i-segmenty-podpischikov-i-delaetsya-ehto-vse-ochen-prosto-i-bystro')
        ;
    }


    function it_can_limit_the_output_to_255_chars_on_get_slug_call()
    {
        $this
            ->transform("В сервисе есть мощный инструмент для создания списков подписчиков как по простым, так и по невероятно сложным сценариям. Например, можно легко выделить тех, кто не открыл ни одно ваше письмо или тех, кто ни разу не кликнул по ссылкам. Можно найти всех подписчиков на яндексе и выделить их в отдельный список. В общем, в зависимости от того, какие данные о подписчике вы собираете, можно формировать очень точные выборки и сегменты подписчиков. И делается это все очень просто и быстро.", 255)
            ->shouldMatch('/^[\w-]{1,255}$/')
        ;
    }

    function it_can_limit_the_output_to_120_chars_on_get_slug_call()
    {
        $this
            ->transform("В сервисе есть мощный инструмент для создания списков подписчиков как по простым, так и по невероятно сложным сценариям. Например, можно легко выделить тех, кто не открыл ни одно ваше письмо или тех, кто ни разу не кликнул по ссылкам. Можно найти всех подписчиков на яндексе и выделить их в отдельный список. В общем, в зависимости от того, какие данные о подписчике вы собираете, можно формировать очень точные выборки и сегменты подписчиков. И делается это все очень просто и быстро.", 120)
            ->shouldMatch('/^[\w-]{1,120}$/')
        ;
    }

    function it_can_limit_the_output_to_10_chars_via_construct_on_get_slug_call()
    {
        $this
            ->transform('Россия завершила поставку зенитных ракетных систем С-300 в Иран', 10)
            ->shouldReturn('rossiya-za')
        ;
    }

    function it_can_override_when_a_string_starts_or_ends_with_special_symbol()
    {
        $this
            ->transform(':Times: Путин начнет атаку на Алеппо с прибытием «Адмирала Кузнецова»')
            ->shouldReturn('times-putin-nachnet-ataku-na-aleppo-s-pribytiem-admirala-kuznecova')
        ;
    }
}
