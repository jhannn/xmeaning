<?php

namespace Tests\Tools;

use App\Tools\Holos;
use Tests\TestCase;

define('AUTHOR_1', 'Matheus Lisboa Nobre da Silva');
define('AUTHOR_2', 'Marcos Antonio Leite do Nascimento');
define('TITLE', 'PANORAMA GERAL SOBRE A GEOCONSERVAÇÃO EM NATAL (RN): AMEAÇAS À GEODIVERSIDADE IN SITU E EX SITU');
define('TAG_1', 'Geodiversidade');
define('TAG_2', 'geoconservação');
define('TAG_3', 'geoturismo');
define('TAG_4', 'ecossistema');
define('TAG_5', 'Natal');
define('DATE_CREATED', '2016-11-12');
define('HOLOSABSTRACT', 'A geodiversidade é tida como a variedade de todos os elementos abióticos do planeta, como minerais, rochas, solos, além de elementos hidrológicos e pedológicos, entre outros. É um termo relativamente novo, em voga desde a década de 1990, mas que tem sido usado de forma crescente, sobretudo por estudos nas áreas de educação e divulgação das geociências. Os estudos no Brasil têm sido intensificados nos últimos 10 anos e têm colaborado também com ações de proteção ao meio ambiente, por meio da chamada geoconservação, que procura desenvolver o consumo sustentável dos elementos abióticos do planeta. Em Natal, capital do Rio Grande do Norte, além das belas paisagens, compostas por praias, dunas, falésias, rios, mangues e lagoas, existem diversos registros de usos dos recursos da geodiversidade, sobretudo, de rochas em monumentos e edifícios, históricos e recentes. Todos esses registros, naturais ou não, estão susceptíveis a ameaças que podem provocar desde pequenas modificações até a supressão das ocorrências. Essas ameaças são ampliadas nos centros urbanos, onde a necessidade por constantes mudanças e obras acaba agredindo o ambiente em que a cidade fora instalado. Assim, este trabalho apresenta conceitos de geodiversidade, geoconservação e geoturismo e mostra as principais ameaças à diversidade abiótica na cidade do Natal.');
define('PDF_LENGTH', 1828330);
define('INTRODUCTION', [
	'As geociências têm experimentado nas últimas três décadas uma expansão de novos conceitos e terminologias que interpretam o planeta como um organismo vivo e de dinâmica constante. Dentre as novas definições, são encontrados os termos “geodiversidade”, “geoconservação” e “geoturismo”. A partir dos quais a comunidade geocientífica passou a pensar e criar projetos de estudo, divulgação e proteção do meio abiótico, objetivando que sua exploração possa ser, em muitos casos, realizada de forma mais sustentável.',
	'A geodiversidade compreende todas as formas e expressões abióticas da Terra, é, por si só, a moradia de todos os seres vivos e tem-se desenvolvido ao longo de 4,6 bilhões de anos. Geoconservação é tida como a proteção aos elementos abióticos por meio de ações de conscientização, divulgação e de estímulo de um consumo sustentável dos recursos naturais e tem como uma das principais ferramentas de divulgação/promoção o geoturismo, atividade econômica que vem sendo desenvolvida em todo o planeta lançando mão de atrativos naturais abióticos na realização de roteiros e atividades turísticas.',
	'A cidade do Natal, capital do Rio Grande do Norte, está emoldurada por uma diversidade abiótica única, composta por campos dunares, falésias, rios, riachos, lagoas, recifes de arenito e manguezais.',
	'No começo da história da cidade, havia vasta área de dunas vegetadas sem intervenção antrópica. Os arenitos calcíferos, dos arrecifes, e ferruginosos, da Formação Barreiras, encontrados nas praias eram elementos de grande importância na representação cartográfica da área e nas construções da época, o que caracteriza uma arquitetura vernacular, em que os recursos locais são utilizados nas edificações.',
	'Com o passar dos séculos, a relação da comunidade local com o ambiente natural da cidade foi sendo modificada, as praias passaram a ser mais valorizadas, até serem hoje as regiões mais cobiçadas. Entretanto, o que permanece mesmo atualmente é a importância e imponência dos recursos da geodiversidade na capital potiguar.',
	'A diversidade abiótica em Natal, assim como em todo o mundo, possui valores associados, que são caracterizados por metodologias apresentadas na literatura especializada. A definição de valores para os locais é ferramenta fundamental no embasamento a ações de proteção, que compõem, para o meio abiótico, a geoconservação.',
	'Em Natal, alguns locais em que elementos da geodiversidade possuem destaque sofrem constantemente com ameaças que vão desde pichações a poluição, por agentes públicos ou algum indivíduo da comunidade. Estas ameaças podem suprimir e descaracterizar a geodiversidade local e precisam ser mitigadas e inibidas.',
	'Por tal razão, este trabalho apresenta as principais ameaças à geodiversidade natalense, seja ela ocorrendo como representante do patrimônio natural da cidade ou como um constituinte do patrimônio histórico/cultural da comunidade local.'
]);
define('CONCLUSION', [
	'Natal é uma cidade em que a geodiversidade é proeminente em suas paisagens, sendo também encontrada como elemento construtivo em regiões e edificações históricas, erguidas desde o século XVII.',
	'Essa geodiversidade, no entanto, sofre com ameaças que são responsáveis não somente por poluição que afeta à população, mas que agride o ambiente natural onde a cidade foi erguida e que é ainda hoje relevante para o desenvolvimento desta ao longo dos séculos.',
	'Disto posto, é fundamental que o conceito da geodiversidade seja divulgado e mais amplamente utilizado nos estudos que versem sobre a proteção do meio ambiente, de forma a criar a consciência na comunidade científica e leiga local de que os recursos abióticos do planeta são tão importantes quanto os seres vivos, fauna e flora, amplamente estudados e protegidos, bem como também amplamente usado em estudos do patrimônio cultural quando neste são',
	'encontrados recursos da geodiversidade, como é o caso do uso de material pétreo em inúmeras construções.'
]);
define('PDFURL', 'http://www2.ifrn.edu.br/ojs/index.php/HOLOS/article/download/4743/1580');

class HolosTest extends TestCase
{
	public function testGetReturnsOk()
	{
		$result = Holos::get(4743);
		$this->assertContains(TITLE, $result);
		$this->assertContains(AUTHOR_1, $result);
		$this->assertContains(AUTHOR_2, $result);
	}

	public function testGetAnInexistentDocument()
	{
		$this->assertFalse(Holos::get(-123213));
	}

	public function testParseAnCachedResult()
	{
		$result = Holos::parse(file_get_contents($this->resource('holos', 'holosdocument1.html')));

		$this->assertArrayHasKey('authors', $result);
		$this->assertCount(2, $result['authors']);
		$this->assertContains(AUTHOR_1, $result['authors']);
		$this->assertContains(AUTHOR_2, $result['authors']);

		$this->assertArrayHasKey('tags', $result);
		$this->assertCount(5, $result['tags']);
		$this->assertContains(TAG_1, $result['tags']);
		$this->assertContains(TAG_2, $result['tags']);
		$this->assertContains(TAG_3, $result['tags']);
		$this->assertContains(TAG_4, $result['tags']);
		$this->assertContains(TAG_5, $result['tags']);

		$this->assertArrayHasKey('title', $result);
		$this->assertEquals(TITLE, $result['title']);
		$this->assertArrayHasKey('date', $result);
		$this->assertEquals(DATE_CREATED, $result['date']);
		$this->assertArrayHasKey('abstract', $result);
		$this->assertEquals(HOLOSABSTRACT, $result['abstract']);
		$this->assertArrayHasKey('pdfUrl', $result);
		$this->assertEquals(PDFURL, $result['pdfUrl']);
	}

	public function testParseAnInvalidHtml()
	{
		$this->expectException('\App\Tools\HolosInvalidDocument');
		Holos::parse('non valid xml content');
	}

	public function testParseValidHTMLWithoutHead()
	{
		$this->expectException('\App\Tools\HolosInvalidDocument');
		Holos::parse('<html><body></body></html>');
	}

	public function testParseValidXMLWithoutMetadata()
	{
		$this->expectException('\App\Tools\HolosInvalidDocument');
		Holos::parse(file_get_contents($this->resource('holos', 'holosdocument_withoutmetadata.html')));
	}

	public function testDownloadAValidPdfUrl()
	{
		$result = Holos::downloadPdf("http://www2.ifrn.edu.br/ojs/index.php/HOLOS/article/download/4743/1580");
		$this->assertTrue(file_exists($result));
		$this->assertEquals(PDF_LENGTH, filesize($result));
	}

	public function testDownloadAnInvalidPdfUrl()
	{
		$result = Holos::downloadPdf("url invalida");
		$this->assertFalse($result);
	}

	public function testDownloadANonPdfUrl()
	{
		$result = Holos::downloadPdf("https://www2.ifrn.edu.br");
		$this->assertFalse($result);
	}

	public function testDownloadADocumentAndCheckTheData()
	{
		$result = Holos::document(4743);

		$this->assertArrayHasKey('authors', $result);
		$this->assertCount(2, $result['authors']);
		$this->assertContains(AUTHOR_1, $result['authors']);
		$this->assertContains(AUTHOR_2, $result['authors']);

		$this->assertArrayHasKey('tags', $result);
		$this->assertCount(5, $result['tags']);
		$this->assertContains(TAG_1, $result['tags']);
		$this->assertContains(TAG_2, $result['tags']);
		$this->assertContains(TAG_3, $result['tags']);
		$this->assertContains(TAG_4, $result['tags']);
		$this->assertContains(TAG_5, $result['tags']);

		$this->assertArrayHasKey('title', $result);
		$this->assertEquals(TITLE, $result['title']);
		$this->assertArrayHasKey('date', $result);
		$this->assertEquals(DATE_CREATED, $result['date']);
		$this->assertArrayHasKey('abstract', $result);
		$this->assertEquals(HOLOSABSTRACT, $result['abstract']);
		$this->assertArrayHasKey('pdfUrl', $result);
		$this->assertEquals(PDFURL, $result['pdfUrl']);

		$this->assertTrue(file_exists($result['pdfTmp']));
	}
}
