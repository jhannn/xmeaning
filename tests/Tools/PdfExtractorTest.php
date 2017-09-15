<?php

namespace Tests\Tools;

use App\Tools\PdfExtractor;
use Tests\TestCase;

define('PDFEXTRACTOR_HOLOS_INTRO', [
	"As geociências têm experimentado nas últimas três décadas uma expansão de novos conceitos e terminologias que interpretam o planeta como um organismo vivo e de dinâmica constante. Dentre as novas definições, são encontrados os termos “geodiversidade”, “geoconservação” e “geoturismo”. A partir dos quais a comunidade geocientífica passou a pensar e criar projetos de estudo, divulgação e proteção do meio abiótico, objetivando que sua exploração possa ser, em muitos casos, realizada de forma mais sustentável.",
	"A geodiversidade compreende todas as formas e expressões abióticas da Terra, é, por si só, a moradia de todos os seres vivos e tem-se desenvolvido ao longo de 4,6 bilhões de anos. Geoconservação é tida como a proteção aos elementos abióticos por meio de ações de conscientização, divulgação e de estímulo de um consumo sustentável dos recursos naturais e tem como uma das principais ferramentas de divulgação/promoção o geoturismo, atividade econômica que vem sendo desenvolvida em todo o planeta lançando mão de atrativos naturais abióticos na realização de roteiros e atividades turísticas.",
	"A cidade do Natal, capital do Rio Grande do Norte, está emoldurada por uma diversidade abiótica única, composta por campos dunares, falésias, rios, riachos, lagoas, recifes de arenito e manguezais.",
	"No começo da história da cidade, havia vasta área de dunas vegetadas sem intervenção antrópica. Os arenitos calcíferos, dos arrecifes, e ferruginosos, da Formação Barreiras, encontrados nas praias eram elementos de grande importância na representação cartográfica da área e nas construções da época, o que caracteriza uma arquitetura vernacular, em que os recursos locais são utilizados nas edificações.",
	"Com o passar dos séculos, a relação da comunidade local com o ambiente natural da cidade foi sendo modificada, as praias passaram a ser mais valorizadas, até serem hoje as regiões mais cobiçadas. Entretanto, o que permanece mesmo atualmente é a importância e imponência dos recursos da geodiversidade na capital potiguar.",
	"A diversidade abiótica em Natal, assim como em todo o mundo, possui valores associados, que são caracterizados por metodologias apresentadas na literatura especializada. A definição de valores para os locais é ferramenta fundamental no embasamento a ações de proteção, que compõem, para o meio abiótico, a geoconservação.",
	"Em Natal, alguns locais em que elementos da geodiversidade possuem destaque sofrem constantemente com ameaças que vão desde pichações a poluição, por agentes públicos ou algum indivíduo da comunidade. Estas ameaças podem suprimir e descaracterizar a geodiversidade local e precisam ser mitigadas e inibidas.",
	"Por tal razão, este trabalho apresenta as principais ameaças à geodiversidade natalense, seja ela ocorrendo como representante do patrimônio natural da cidade ou como um constituinte do patrimônio histórico/cultural da comunidade local."
]);

class PdfExtractorTest extends TestCase
{
	public function testJarPath()
	{
		$this->assertTrue(file_exists(PdfExtractor::jarPath()), "PDFExtractor não compilado.");
	}

	public function testExtractMinuteStructured()
	{
		$result = PdfExtractor::extract('minute', self::resource('atas', 'estruturadas', '1.pdf'));
		$this->assertEquals('Mesa de Negociação do SUS - Belo Horizonte', $result['title']['text']);
		$this->assertEquals('2 de abril de 2008', $result['date']['text']);

		$this->assertTrue(is_array($result['attendees']));
		$this->assertEquals(7, count($result['attendees']));
		$this->assertEquals(3, count($result['attendees'][2]['personas']));
		$this->assertEquals('Valma Zulato', $result['attendees'][2]['personas'][1]['name']['text']);

		$this->assertEquals(2, count($result['agenda']));
		$this->assertEquals('Informes e assuntos gerais', $result['agenda'][1]['textObject']['text']);

		$this->assertEquals(24, count($result['discussion']));
		$this->assertEquals('Aumento da Demanda nos Centros de Saúde do NE devido à dengue', $result['discussion'][1]['text']);
		$this->assertEquals('Cândida do CS Gentil Gomes fala sobre a situação atual da unidade, os trabalhadores estão adoecendo, médica pediu exoneração, e a comunidade está sem assistência, o PSF está indo de água abaixo, a situação é emergencial', $result['discussion'][8]['text']);
		$this->assertEquals('Warlene informa que todos irão migrar, 4 turmas de 450 ACS, os casos na justiça ainda não foram discutidos, e solicitou os nomes.', $result['discussion'][20]['text']);

		$this->assertTrue(empty($result['reports']));

		$this->assertEquals(1, count($result['referrals']));
		$this->assertEquals('Nenhum', $result['referrals'][0]['textObject']['text']);
	}

	public function testExtractHolos()
	{
		$result = PdfExtractor::extract('holos', self::resource('holos', 'pdf1.pdf'));
		$this->assertEquals(PDFEXTRACTOR_HOLOS_INTRO[0], $result['introduction'][0]['text']);
		$this->assertEquals(PDFEXTRACTOR_HOLOS_INTRO[1], $result['introduction'][1]['text']);
		$this->assertEquals(PDFEXTRACTOR_HOLOS_INTRO[2], $result['introduction'][2]['text']);
		$this->assertEquals(PDFEXTRACTOR_HOLOS_INTRO[3], $result['introduction'][3]['text']);
		$this->assertEquals(PDFEXTRACTOR_HOLOS_INTRO[4], $result['introduction'][4]['text']);
		$this->assertEquals(PDFEXTRACTOR_HOLOS_INTRO[5], $result['introduction'][5]['text']);
		$this->assertEquals(PDFEXTRACTOR_HOLOS_INTRO[6], $result['introduction'][6]['text']);
		$this->assertEquals(PDFEXTRACTOR_HOLOS_INTRO[7], $result['introduction'][7]['text']);
	}

	public function testNoExistingPdf()
	{
		$this->expectException('Illuminate\Contracts\Filesystem\FileNotFoundException');
		PdfExtractor::extract('article', 'nonexistingfile.pdf');
	}
}
