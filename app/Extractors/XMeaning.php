<?php

namespace App\Extractors;


class XMeaning
{
	private static $words = ['Resolução', 'julgamento', 'decisão', 'deliberação', 'solução', 'despacho', 'arbitramento', 'brocardo', 'resultado', 'propósito', 'resolução', 'determinação', 'definição', 'sentença', 'desempate', 'robora', 'juízo', 'julgado', 'mandato', 'frase', 'acórdão', 'lema', 'veredicto', 'acordão', 'desembargo', 'aresto', 'sentença', 'processo', 'eleição', 'divisão', 'solvência', 'pesquisa', 'consulta', 'alvitre', 'conselho', 'opinião', 'parecer', 'sugestão', 'arbítrio', 'cômputo', 'orçamento', 'cálculo', 'estimativa', 'transformação', 'transmutação', 'conversão', 'convertimento', 'transmudação', 'transmudamento', 'beatificação', 'canonização', 'dactiloscopia', 'datiloscopia', 'dactilomancia', 'Acordo', 'conformidade', 'concordância', 'compadecimento', 'ortodoxia', 'coerência', 'união', 'acordo', 'concórdia', 'entendimento', 'concertação', 'comunhão', 'consenso', 'conformação', 'harmonia', 'conciliação', 'congraçamento', 'consonância', 'adesão', 'assentimento', 'liança', 'unanimidade', 'reconciliação', 'consentimento', 'assentamento', 'concordança', 'diapasão', 'assonância', 'concento', 'homocatalexia', 'acordança', 'confraternização', 'fraternidade', 'fraternização', 'paz', 'bravo', 'apoiado', 'aprovação', 'sufrágio', 'acolhimento', 'apoio', 'confirmação', 'admissão', 'aceitação', 'aceitamento', 'aplauso', 'legalização', 'validação', 'bem-querença', 'acolhida', 'atendimento', 'recebimento', 'recepção', 'aliança', 'afinidade', 'simpatia', 'compatibilidade', 'Pacto', 'tratado', 'convênio', 'pacto', 'tratados', 'contrato', 'negócio', 'convénio', 'convenção', 'ajuste', 'concerto', 'preito', 'preitesia', 'pauto', 'pautas', 'aveniência', 'concordata', 'Consenso', 'conformidade', 'concordância', 'compadecimento', 'ortodoxia', 'coerência', 'união', 'acordo', 'concórdia', 'entendimento', 'concertação', 'comunhão', 'consenso', 'conformação', 'harmonia', 'conciliação', 'congraçamento', 'consonância', 'adesão', 'assentimento', 'liança', 'unanimidade', 'reconciliação', 'consentimento', 'assentamento', 'concordança', 'diapasão', 'assonância', 'concento', 'homocatalexia', 'acordança', 'confraternização', 'fraternidade', 'fraternização', 'paz', 'bravo', 'apoiado', 'aprovação', 'sufrágio', 'acolhimento', 'apoio', 'confirmação', 'admissão', 'aceitação', 'aceitamento', 'aplauso', 'legalização', 'validação', 'bem-querença', 'acolhida', 'atendimento', 'recebimento', 'recepção', 'aliança', 'afinidade', 'simpatia', 'compatibilidade', 'Conflito', 'atrito', 'desentendimento', 'divisão', 'discórdia', 'zanga', 'bulha', 'cisma', 'rixa', 'desavença', 'discrepância', 'desunião', '  dissenção', 'quizila', 'desacordo', 'dissensão', 'desarmonia', 'dissenso', 'dissentimento', 'desinteligência', 'indisposição', 'inimizade', 'cizânia', 'malavença', 'incha', 'desconciliação', 'animosidade', 'incompatibilidade', 'quezília', 'antipatia', 'quezila', 'conflito', 'batalha', 'Reivindicação', 'grito', 'gritar', 'alerta', 'alarme', 'alarma', 'repique', 'viva', 'berra', 'brama', 'hurra', 'crocitar', 'rasga-mortalha', 'a-d\'el-rei', 'reclamo', 'voz', 'protesto', 'queixa', 'reivindicação', 'reclamação', 'clamor', 'requisição', 'brado', 'berro', 'vindicação', 'vozear', 'vindícia', 'exclamação', 'intervenção', 'interjeição', 'interpolação', 'Dissenso', 'temporal', 'desavença', 'atrito', 'desentendimento', 'divisão', 'discórdia', 'zanga', 'bulha', 'cisma', 'rixa', 'discrepância', 'desunião', 'dissenção', 'quizila', 'desacordo', 'dissensão', 'desarmonia', 'dissenso', 'dissentimento', 'desinteligência', 'indisposição', 'inimizade', 'cizânia', 'malavença', 'incha', 'desconciliação', 'parada', 'luta', 'combate', 'debate', 'competição', 'desafio', 'certame', 'disputa', 'operação', 'tenção', 'oposição', 'conflito', 'contenda', 'pendência', 'demanda', 'pleito', 'discussão', 'briga', 'cena', 'gládio', 'transe', 'rota', 'altercação', 'antagonismo', 'justa', 'peleja', 'recontro', 'testilha', 'arrancada', 'rivalidade', 'porfia', 'querela', 'lide', 'refrega', 'pugna', 'prélio', 'renhimento', 'interquinência', 'enterquinência', 'maca', 'tira-puxa', 'pontinha', 'confronto', 'dissolução', 'trisca', 'bateria', 'rusga', 'pega', 'escaramuça', 'liça', 'quezília', 'questiúncula', 'turra', 'desaguisado', 'pendenga', 'bataria', 'rinha', 'discrime', 'pendença', 'requesta', 'leia', 'referta', 'disputação', 'quizília', 'parlenga', 'luita', 'combatimento', 'pegadilha', 'turumbamba', 'derriça', 'arrepeladela', 'liorta', 'galana', 'laneiro', 'dize-tu-direi-eu', 'digladiação', 'renzilha', 'destêmpera', 'calcada', 'vuvu', 'combato', 'baixaria', 'gambérria', 'parlanda', 'ralhos', 'peleona', 'maluta', 'ofensão', 'fecha-fecha', 'cu-de-boi', 'guerreia', 'quibeto', 'tràvacontas', 'estrupício', 'agitação', 'fúria', 'perturbação', 'desordem', 'sarilho', 'encrenca', 'tumulto', 'espalhafato', 'confusão', 'barulho', 'turbulência', 'motim', 'alvoroço', 'zaragata', 'frevo', 'desestabilização', 'barafunda', 'sururu', 'turvação', 'presepada', 'fuzuê', 'bruega', 'bochincho', 'toiraria', 'bafô', 'brequefeste', 'touraria', 'sangangu', 'seribolo', 'banguelê', 'ataque', 'partida', 'jogo', 'concurso', 'torneio', 'match', 'prova', 'batalha', 'quebra', 'cisão', 'divórcio', 'divorcio'];

	public static function extract($text)
	{
		$result = [];
		foreach (self::$words as $word)
		{
			if (preg_match("/\\b${word}\b/", $text))
				array_push($result, [
					'text' => $word,
					'relevance' => 1
				]);
		}
		return $result;
	}
}