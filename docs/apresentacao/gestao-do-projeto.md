# Do problema ao software em uso

[Abrir versão em slides](gestao-do-projeto.html). No navegador, use as setas para navegar, N para notas do professor e F para tela cheia. O botão Imprimir / PDF inclui todos os slides.

## Guia de gestão e desenvolvimento da votação escolar

Este material complementa os slides técnicos. Ele explica **como pensar e conduzir o projeto inteiro**, usando a galeria de arte como exemplo. Os comandos de Laravel são uma parte da execução; as decisões sobre pessoas, regras, prioridades, qualidade e operação vêm antes e continuam depois do código.

**Pergunta central:** como permitir que a comunidade escolar conheça as obras, vote com facilidade e receba um resultado confiável?

O caminho é:

**Entender → Definir → Desenhar → Planejar → Construir → Verificar → Colocar em uso → Acompanhar.**

Esse caminho tem retornos. Se um teste com alunos revelar uma fila demorada, voltamos ao fluxo e à identificação do eleitor. Não precisamos descartar o projeto, mas precisamos revisar a decisão.

> Situação da aplicação usada como exemplo: já existem galeria, administração, upload, voto por e-mail informado, apuração e PDF. Códigos individuais, QR Code e modo cabine são propostas de evolução, não funcionalidades prontas. Os prazos e metas deste guia são exercícios de planejamento, não compromissos definidos pela escola.

## 1. Entender o problema antes de escolher a solução

Começar pelo banco ou pela tela sem entender o evento pode produzir um sistema que funciona tecnicamente, mas não atende à escola.

Converse com quem organiza, quem vota e quem acompanha o laboratório. Se possível, observe como a votação acontece hoje.

| Pergunta | Por que muda o projeto? |
|---|---|
| Qual é o objetivo da votação? | Define o que significa sucesso. |
| Quem pode votar? | Define elegibilidade e distribuição de acesso. |
| Quantos participantes e obras são esperados? | Orienta capacidade, navegação e ensaio de carga. |
| Cada pessoa escolhe uma obra ou várias? | Altera a regra e o modelo de dados. |
| Todos votam na mesma exposição? | Define o limite de repetição. |
| Haverá celulares próprios ou computadores compartilhados? | Altera o fluxo e a gestão de sessões. |
| A internet e a rede local são confiáveis? | Orienta implantação e contingência. |
| Quem pode abrir e encerrar a votação? | Define permissões e responsabilidades. |
| O resultado aparece durante o evento? | Pode influenciar votos e muda as telas públicas. |
| Qual é a data do evento? | Define a janela real de entrega e ensaio. |

**Exemplo de problema bem formulado:** “Precisamos registrar a escolha do público na exposição escolar, reduzir o trabalho manual de contagem e entregar um relatório conferível ao final.”

**Entrega desta etapa:** uma página com problema, público, contexto, restrições e dúvidas abertas.

**Condição para avançar:** a organização reconhece que essa descrição representa sua necessidade.

## 2. Identificar as pessoas e as decisões

Não basta saber quem programa. É preciso saber quem esclarece uma regra e quem aceita a entrega.

| Papel | Responsabilidade no exemplo |
|---|---|
| Organização da exposição | Decide regras, prioridade e aceitação do sistema. |
| Professor orientador | Ajuda a equipe a planejar, revisar e aprender. |
| Equipe de desenvolvimento | Modela, implementa, testa e documenta. |
| Curadoria | Cadastra as obras e opera abertura e encerramento. |
| Eleitores | Usam a galeria e participam dos testes de facilidade de uso. |
| Responsável pela infraestrutura | Prepara servidor, rede, acesso e backup. |
| Responsável pelo atendimento | Resolve dúvidas e orienta a fila no evento. |

Uma pessoa pode acumular papéis. Mesmo assim, cada tarefa deve ter um responsável identificável. Em uma dupla, alternem programação e revisão; ambos precisam entender o percurso completo do voto.

**Entrega:** lista de responsáveis e indicação de quem resolve cada tipo de dúvida.

## 3. Definir sucesso com evidências

“O sistema ficou bonito” não comprova que a votação funciona.

Definam indicadores antes de construir. Exemplos para negociar com a escola:

- Um participante consegue concluir a votação sem ajuda após uma orientação inicial.
- Um segundo voto com o mesmo identificador é recusado na mesma exposição.
- Nenhum voto é aceito depois do encerramento.
- Os totais do banco, do painel e do PDF conferem no conjunto de teste.
- A fila consegue atender o público previsto dentro da duração do evento.

**Exercício de capacidade:** supondo 300 participantes em 60 minutos, a escola precisa atender em média 5 pessoas por minuto. Se cada pessoa ocupa uma cabine por 30 segundos, cada cabine atende teoricamente 2 pessoas por minuto. Três cabines seriam um mínimo teórico, sem considerar dúvidas, deslocamento ou falhas. Um ensaio deve determinar a quantidade real e a margem necessária.

Não confundam velocidade do servidor com duração do atendimento. O aluno pode passar mais tempo escolhendo a obra ou digitando uma identificação do que esperando uma resposta do sistema.

**Entrega:** critérios de sucesso e um plano simples de como medi-los.

## 4. Delimitar o escopo da primeira versão

Escopo é o acordo sobre o que será entregue. Uma boa ideia não precisa entrar imediatamente.

| Essencial para a primeira versão | Pode ficar para uma evolução |
|---|---|
| Login administrativo | Cadastro público de artistas |
| Criar exposição e cadastrar imagens | Categorias e filtros avançados |
| Mostrar a galeria | Animações elaboradas |
| Receber votos conforme a regra escolhida | Avaliação por notas e múltiplos critérios |
| Impedir repetição pelo identificador | Integração com outros sistemas escolares |
| Abrir e encerrar votação | Programação automática de eventos |
| Apurar e exportar PDF | Painéis estatísticos adicionais |

**Atenção à identificação:** e-mail, código e matrícula são alternativas que precisam ser avaliadas, não três funcionalidades obrigatórias. Se o ensaio mostrar que o e-mail não atende à fila, o código individual pode virar requisito essencial para o evento, deixando de ser uma melhoria opcional.

**Fora do escopo inicial, salvo acordo:** comprovar identidade civil, eleger representantes com regras legais específicas ou funcionar sem qualquer conexão de rede. Não presumam essas capacidades apenas porque o sistema recebe votos.

**Entrega:** uma lista “entra agora”, “fica depois” e “não faz parte”, aceita pela organização.

## 5. Transformar expectativas em requisitos claros

Separem três coisas:

- **Funcionalidade:** o sistema permite cadastrar uma obra.
- **Regra de negócio:** só é permitido cadastrar obras antes da abertura.
- **Qualidade esperada:** o formulário deve funcionar no dispositivo usado pela curadoria e explicar os erros de envio.

Uma história de usuário ajuda a explicar a finalidade:

> Como curador, quero cadastrar uma imagem com título para que os participantes possam conhecer e escolher a obra.

Mas a história sozinha não define quando terminou. Acrescentem critérios de aceitação:

1. Título e imagem são obrigatórios; artista é opcional.
2. JPG, PNG e WebP são aceitos dentro dos limites definidos.
3. Um arquivo inválido produz uma mensagem compreensível.
4. A imagem aparece na exposição correta.
5. Depois de abrir a votação, novos cadastros são recusados pelo servidor.

**Outro exemplo, em formato Dado / Quando / Então:**

> Dado que a exposição está encerrada, quando alguém tenta confirmar uma escolha, então nenhum voto é gravado e a pessoa recebe uma mensagem informando o encerramento.

**Entrega:** requisitos numerados, critérios verificáveis e dúvidas que ainda dependem de decisão.

## 6. Desenhar a jornada antes das telas detalhadas

Desenhem em papel os caminhos principais:

**Curadoria:** entrar → criar exposição → cadastrar obras → revisar → abrir → acompanhar → encerrar → baixar resultado.

**Eleitor:** acessar exposição → explorar imagens → escolher → informar identificação → confirmar → receber comprovante visual de sucesso.

Para cada ação, desenhem também uma falha:

| Ação | O que pode dar errado? | Resposta esperada |
|---|---|---|
| Entrar no painel | Senha incorreta | Mensagem sem abrir acesso. |
| Enviar imagem | Arquivo inválido ou grande demais | Explicar a correção necessária. |
| Abrir votação | Nenhuma obra cadastrada | Bloquear a abertura e orientar. |
| Confirmar voto | Identificador já utilizado | Recusar a repetição. |
| Confirmar voto | A votação foi encerrada durante a escolha | Recusar e informar o encerramento. |
| Receber confirmação | A conexão caiu depois da gravação | Nova tentativa não pode duplicar o voto. |

Peçam a alguém de fora da equipe para percorrer o desenho. Se a pessoa não entende onde clicar no papel, escrever código não resolverá esse problema automaticamente.

**Entrega:** esboços das telas e fluxos de sucesso e erro. O detalhamento visual vem depois de validar a navegação.

## 7. Modelar os dados a partir das regras

Agora há informação suficiente para pensar no banco.

| Entidade | Pergunta que responde |
|---|---|
| Usuário administrativo | Quem pode gerenciar? |
| Exposição | Em qual evento as obras participam e qual é sua situação? |
| Obra | O que está sendo exibido e onde está a imagem? |
| Voto | Qual obra recebeu uma escolha nesta exposição? |

Relações: uma exposição possui muitas obras e muitos votos; uma obra possui muitos votos. O eleitor não precisa ser um usuário administrativo.

Definam:

- Chaves primárias para identificar registros.
- Chaves estrangeiras para impedir referências inexistentes.
- Um índice único por exposição e identificador de eleitor.
- Campos obrigatórios, opcionais e limites de tamanho.
- O que acontece com os registros relacionados em uma exclusão.
- Quais dados realmente precisam ser coletados e por quanto tempo serão mantidos.

No projeto atual, a imagem fica no armazenamento de arquivos e o banco guarda seu caminho. O nome do artista pode ser deixado em branco. A ausência do artista é gravada como texto vazio para compatibilidade com a coluna existente.

O identificador de eleitor deriva do e-mail, mas não confirma quem digitou o endereço. Se a escolha for usar cartões, revisem o modelo para incluir códigos, vínculo com exposição e controle de uso.

**Entrega:** diagrama e dicionário dos campos. Validem o modelo com os casos de uso, especialmente repetição, empate, zero votos e encerramento.

## 8. Definir a arquitetura e preparar o trabalho

Arquitetura é decidir onde cada responsabilidade fica e como as partes conversam.

| Parte | Papel neste sistema |
|---|---|
| Navegador / Blade / CSS / JavaScript | Apresentar as telas e interações. |
| Rotas | Direcionar a requisição para a ação correta. |
| Middleware | Controlar autenticação, autorização e limites. |
| Controller | Validar entradas e coordenar o caso de uso. |
| Model / Eloquent | Consultar e persistir dados relacionados. |
| MySQL | Guardar registros e garantir restrições. |
| Armazenamento | Guardar imagens enviadas. |
| Gerador de PDF | Produzir o relatório a partir de dados apurados. |

Preparem um repositório versionado, instruções de execução e configurações de exemplo. Não incluam senhas ou dados reais no repositório. Separem banco de desenvolvimento, teste e uso real.

O banco deve ser reproduzível por migrations. Um colega deve conseguir iniciar a aplicação seguindo a documentação, sem depender da memória de quem a criou.

**Entrega:** ambiente funcionando e primeiro incremento executável. No projeto de referência, a base antiga de Laravel/PHP precisa ser revista antes de uma publicação real.

## 9. Montar uma lista priorizada de entregas

A lista de trabalho, ou backlog, organiza o que falta. Escrevam itens que entreguem comportamento observável, não apenas nomes de arquivos.

| ID | Entrega | Depende de | Evidência de conclusão |
|---|---|---|---|
| P01 | Preparar ambiente e banco | Regras e configuração | Outro colega inicia a aplicação. |
| P02 | Proteger a administração | P01 | Visitante não acessa funções administrativas. |
| P03 | Criar exposição em preparação | P02 | Curadoria cria e visualiza a exposição. |
| P04 | Cadastrar e exibir obras | P03 | Imagem enviada aparece na exposição correta. |
| P05 | Abrir votação com obras | P04 | Exposição fica pública; cadastro é bloqueado. |
| P06 | Registrar voto com controle de repetição | P05 e identificação decidida | Voto válido entra, repetido não entra. |
| P07 | Encerrar votação | P06 | Novos votos são recusados. |
| P08 | Mostrar apuração | P06 | Contagem confere com o conjunto de teste. |
| P09 | Exportar PDF | P08 | Relatório legível, com total e situação corretos. |
| P10 | Ensaiar e preparar o evento | P07, P09 | Organização aceita a versão no ambiente de uso. |

Dentro de P04, por exemplo, podem existir tarefas de migration, model, rota, formulário, validação e armazenamento. A entrega só termina quando essas partes funcionam juntas.

**Prioridade não é ordem alfabética nem gosto pessoal.** Considerem valor, dependências, risco e data do evento. Testem cedo a maior dúvida: se a fila depende de códigos rápidos, façam um pequeno protótipo desse fluxo antes de investir em acabamento visual.

## 10. Estimar, distribuir e acompanhar

Estimativa é uma previsão baseada no que sabemos, não uma garantia.

1. Dividam entregas grandes em tarefas que caibam em uma sessão de trabalho.
2. Peçam a quem executará para estimar e apontar dúvidas.
3. Incluam revisão, integração, testes, documentação e correções.
4. Reservem tempo para ensaio e imprevistos antes do evento.
5. Atualizem a previsão conforme entregas reais forem concluídas.

**Exemplo de quatro ciclos de trabalho, com duração a definir pela turma:**

| Ciclo | Resultado demonstrável |
|---|---|
| 1 | Administrador cria exposição e mostra uma obra. |
| 2 | Eleitor registra escolha e repetição é bloqueada. |
| 3 | Curadoria encerra, confere apuração e baixa PDF. |
| 4 | Organização ensaia, equipe corrige e prepara operação. |

Usem um quadro simples: **A fazer → Em andamento → Em revisão/teste → Concluído**. Um cartão bloqueado deve explicar o motivo e quem pode resolvê-lo.

No início de cada encontro, respondam: o que foi entregue, qual é a próxima entrega e o que impede o avanço? Ao final, mostrem algo funcionando. “Fizemos 12 arquivos” informa menos que “já conseguimos cadastrar e visualizar uma obra”.

Evitem muitas tarefas abertas. Em uma dupla, é melhor terminar e revisar um fluxo antes de iniciar cinco telas desconectadas.

## 11. Implementar por percursos completos

Construam pequenas entregas que atravessam tela, servidor e banco. Isso revela problemas de integração cedo.

### Primeiro percurso: cadastrar uma exposição

1. Escrever critérios de aceitação.
2. Criar ou revisar a migration.
3. Definir o model e os campos permitidos.
4. Criar rota protegida e ação no controller.
5. Criar formulário com CSRF e mensagens de erro.
6. Validar no servidor e gravar.
7. Redirecionar para a exposição criada.
8. Conferir a persistência e o acesso de visitantes.
9. Pedir revisão e registrar a entrega no repositório.

### Segundo percurso: enviar uma obra e vê-la na galeria

Repitam a sequência, acrescentando validação do arquivo, armazenamento, vínculo com a exposição e limpeza caso a gravação falhe.

### Terceiro percurso: votar e conferir o registro

Validem identificação, situação da exposição e vínculo da obra. A consulta e a gravação precisam acontecer com proteção contra concorrência. No projeto atual, o voto e a mudança de estado usam transação e bloqueio da exposição; o índice único reforça a regra de repetição.

### Quarto percurso: encerrar e entregar o resultado

Conectem mudança de estado, recusa de novos votos, classificação e exportação. Empates e ausência de votos precisam estar previstos, não improvisados na apresentação final.

**Definição de concluído para qualquer percurso:** critérios atendidos, erros tratados, permissões verificadas, revisão realizada, verificações relevantes aprovadas e documentação atualizada quando necessário.

## 12. Enxergar uma ação de ponta a ponta

Para compreender o software inteiro, acompanhem um voto:

| Momento | O que acontece | Pergunta de revisão |
|---|---|---|
| 1. Interface | Eleitor escolhe uma obra. | Está claro o que será confirmado? |
| 2. Envio | Formulário envia uma requisição POST. | Os campos e o token CSRF são enviados? |
| 3. Entrada no servidor | Rota e middleware processam a requisição. | O limite de uso é adequado à rede escolar? |
| 4. Validação | Controller confere identificação e escolha. | Entrada inválida é recusada? |
| 5. Regra | Confere estado, obra e repetição. | O que ocorre se alguém encerrar agora? |
| 6. Persistência | Banco grava dentro da transação. | Existe proteção contra duplicidade? |
| 7. Resposta | Sistema informa sucesso ou erro. | O aluno sabe se concluiu? |
| 8. Apuração | Consulta conta o novo voto. | O painel corresponde ao banco? |
| 9. Relatório | PDF apresenta um retrato da apuração. | O resultado é parcial ou final? |
| 10. Operação | Equipe acompanha falhas e preserva os dados. | Quem atende se houver problema? |

Saber o projeto de ponta a ponta significa conseguir explicar **quem precisa da função, qual regra ela atende, onde os dados passam, como sabemos que funciona e quem cuida dela em uso**.

Não significa que todos precisam memorizar cada linha. Significa que nenhuma parte fica sem responsável ou sem conexão com a finalidade do projeto.

## 13. Tratar riscos e mudanças durante o trabalho

| Risco | Prevenção ou redução | Responsável sugerido |
|---|---|---|
| Fila demorada | Ensaiar com participantes e medir atendimento. | Organização + equipe |
| Votos repetidos | Definir identificação, validar e aplicar restrições no banco. | Desenvolvimento |
| Vários dispositivos compartilham IP | Testar limites de requisição na rede real. | Desenvolvimento + infraestrutura |
| Falha de energia ou rede | Combinar pausa, comunicação e retomada. | Infraestrutura + organização |
| Perda de dados | Fazer backup e testar restauração antes do evento. | Infraestrutura |
| Obra ou autoria incorreta | Conferência da curadoria antes de abrir. | Curadoria |
| Falta de tempo | Priorizar essenciais e negociar retirada de melhorias. | Organização + equipe |
| Dependências antigas | Atualizar e verificar antes da implantação. | Desenvolvimento |

Não inventem um modo offline ou contagem alternativa durante o incidente. Se houver votação em papel como contingência, a organização deve definir previamente como impedir duplicação e como conferir os resultados. Não misturem totais sem rastreabilidade.

**Mudança de requisito, exemplo:** “Vamos trocar e-mail por código.”

Antes de implementar, registrem:

1. Motivo: reduzir digitação na fila.
2. Partes afetadas: identificação, banco, formulário, distribuição, testes e atendimento.
3. Trabalho novo: gerar cartões, controlar entrega, validar uso e tratar perda.
4. Impacto no prazo: estimativa da equipe e o que precisará sair do ciclo.
5. Decisão: responsável pela organização aceita ou adia a mudança.

Isso evita que uma solicitação aparentemente pequena se transforme em trabalho invisível.

## 14. Verificar o software e validar com a escola

**Verificação:** implementamos corretamente o que foi combinado?

**Validação:** o que foi combinado resolve a necessidade real?

Os testes automatizados ajudam na primeira pergunta. Observar participantes usando o sistema ajuda na segunda. Uma votação pode passar nos testes e ainda ser lenta para a fila.

Planejem quatro grupos de verificações:

- **Regras:** obrigatório/opcional, abertura, encerramento, repetição, obra de outra exposição.
- **Permissões:** visitante não administra nem baixa relatórios privados.
- **Resultados:** votos conhecidos, zero votos, empates, percentuais e PDF.
- **Uso real:** celular/computador, imagem legível, conexão, navegação por teclado e fila.

Criem uma exposição de teste com resultados previamente conhecidos. Exemplo: obra A recebe 3 votos, B recebe 2 e C recebe 0. Total esperado: 5; percentuais: 60%, 40% e 0%.

No projeto atual, os testes automatizados usam SQLite em memória. Isso não substitui o ensaio de integração e concorrência com MySQL no ambiente de uso.

**Entrega:** lista de cenários, resultados, problemas encontrados e evidência da aceitação pela organização. Uma demonstração feliz sozinha não basta.

## 15. Preparar a implantação e o dia do evento

Não deixem a primeira execução no servidor para a manhã da votação.

Antes de abrir ao público:

1. Definir onde o sistema será hospedado e de quais dispositivos será acessado.
2. Instalar uma versão identificada e verificar compatibilidade de dependências.
3. Configurar banco, arquivos, endereço e credenciais do ambiente, sem copiar dados de teste.
4. Fazer backup antes de mudanças no banco; aplicar migrations revisadas.
5. Conferir autenticação, imagens, limites de upload e permissões do armazenamento.
6. Usar HTTPS quando publicado e desativar informações de depuração em produção.
7. Ensaiar o fluxo completo com MySQL, rede e dispositivos do evento.
8. Treinar a curadoria e a equipe que orientará os participantes.
9. Conferir obras e identificação de eleitores antes de abrir.
10. Registrar contatos e procedimentos para pausar, atender e retomar.

O backup precisa incluir banco, imagens e configurações necessárias à recuperação, com acesso restrito. No modelo atual, preservar a APP_KEY é necessário para manter o mesmo cálculo dos identificadores de voto.

Se uma versão apresentar problema, restaurar um banco antigo pode apagar votos recentes. A recuperação precisa considerar os registros recebidos desde o backup; não é apenas “voltar o código”. Definam a estratégia com quem administra o ambiente.

**Condição para abrir:** organização confirma que a versão, os dados e a operação estão preparados.

## 16. Acompanhar, encerrar e aprender

Durante o evento, acompanhem falhas, tempo de atendimento e dúvidas frequentes. Evitem alterar regras ou publicar mudanças sem avaliação no meio da votação.

No encerramento:

1. A pessoa autorizada encerra a exposição.
2. Confere-se que novos votos são recusados.
3. A organização verifica totais e baixa o relatório final.
4. Preservam-se os dados necessários conforme a política combinada.
5. Registram-se incidentes e decisões de atendimento.
6. A equipe entrega documentação e orientações de suporte.

Façam uma retrospectiva curta: o que funcionou, o que causou retrabalho e qual mudança faremos no próximo projeto? Melhorias voltam ao backlog, com prioridade; não precisam ser todas implementadas imediatamente.

**Entrega final do projeto:** software, documentação, evidências de verificação, orientação de uso, responsáveis pela operação e registro das pendências conhecidas.

## Como conduzir este conteúdo com os alunos

Organize o exercício em quatro momentos, antes e durante as aulas técnicas:

| Momento | Trabalho da turma | Pergunta do professor |
|---|---|---|
| Descoberta | Entrevista simulada, problema e regras. | “Para quem estamos construindo e por quê?” |
| Planejamento | Escopo, jornada, dados, backlog e responsáveis. | “Qual é a menor entrega útil e o que vem primeiro?” |
| Execução | Implementação por percursos e demonstrações. | “Como sabemos que esse item está concluído?” |
| Entrega | Ensaio, aceitação e plano de operação. | “O que falta para a escola conseguir usar sem vocês ao lado?” |

Peça que cada grupo apresente seu plano antes de mostrar código. Durante a implementação, use os [slides técnicos](apresentacao.html) e o [roteiro do professor](roteiro-professor.md).

Os grupos podem preencher os [modelos de planejamento](modelos-de-planejamento.md). Avalie tanto o resultado funcionando quanto a capacidade de explicar prioridades, decisões, riscos e evidências.

**Pergunta para fechar a aula:** “Se a votação fosse amanhã, o que ainda impediria o uso pela escola?” A resposta revela a diferença entre ter código e ter uma entrega pronta.
