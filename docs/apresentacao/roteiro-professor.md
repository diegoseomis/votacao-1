# Roteiro do professor — Galeria de arte com Laravel e MySQL

Abra apresentacao.html no navegador. O arquivo é independente, com logo, estilos e scripts incorporados, e funciona sem internet. Use setas para navegar, N para notas e F para tela cheia. O botão Imprimir / PDF imprime todos os slides em paisagem; desative cabeçalhos e rodapés do navegador e mantenha gráficos de fundo. A impressão deve ser conferida na prévia do seu navegador. Não é necessário iniciar o Laravel para apresentar.

## Preparação

- Reserve oito encontros de 50 minutos, adaptando ao conhecimento de PHP e SQL da turma.
- Prepare uma cópia do projeto sem .env, logs, banco de produção ou imagens pessoais. Instale dependências antes da aula e forneça a base pelo caminho A, se necessário.
- A versão de referência é Laravel 10. O código reproduz o projeto existente, não uma recomendação de versão para produção.
- Slides de código são trechos comentados; os arquivos completos estão no projeto. Use apenas um caminho de instalação por dupla.
- Não projete o .env real. Os exemplos usam somente credenciais fictícias.
- Prepare duas imagens autorizadas e uma exposição de teste. Nunca use migrate:fresh no banco real.
- A versão atual vota por e-mail informado; códigos individuais são uma proposta de extensão.

## Sequência de encontros

| Encontro | Conteúdo | Entrega |
|---|---|---|
| 1 | Slides 1–7: problema e modelagem | Diagrama com PK, FK e regra de voto |
| 2 | Slides 8–11: laboratório e banco | Aplicação conectada a votacao_aula |
| 3 | Slides 12–15: migrations, MVC e models | Tabelas e relacionamentos |
| 4 | Slides 16–19: rotas, Blade e administração | Galeria e exposição em preparação |
| 5 | Slides 20–21: upload | Imagens válidas e rejeição de inválidas |
| 6 | Slides 22–24: voto e concorrência | Voto registrado e repetição bloqueada |
| 7 | Slides 25–27: apuração e PDF | Consulta comparada ao relatório |
| 8 | Slides 28–32: testes, evolução e apresentação | Demonstração e explicação técnica |

Sugestão por encontro: 10 min de conceito, 10 min de demonstração, 25 min de prática e 5 min de revisão.

## Notas slide a slide

### 1. Da ideia ao primeiro voto.

Abra a aplicação pronta e demonstre o percurso: criar exposição, cadastrar obra, abrir, votar, encerrar e exportar. Explique que a turma construirá essas peças por etapas. A apresentação não depende da aplicação estar executando.

### 2. O que vamos construir?

Público sugerido: alunos com noções de HTML, CSS, lógica e PHP. Reserve oito encontros de 50 minutos; iniciantes podem precisar de encontros adicionais. Trabalhe em duplas, alternando quem programa e quem revisa.

### 3. Antes de programar, combine as regras.

No projeto atual não há reabertura nem alteração do voto. A abertura exige ao menos uma obra. Os resultados ficam disponíveis apenas para administradores. Pergunte por que adicionar uma obra depois da abertura seria injusto.

**Atividade:** Escrevam três situações que o sistema deve impedir.

### 4. Como evitar que alguém vote duas vezes?

Não apresente códigos como recurso já implementado. Esta aula explica o código existente por e-mail e propõe códigos como atividade de evolução. Não precisamos coletar nomes de alunos para demonstrar o sistema.

### 5. Quais informações precisamos guardar?

Mostre as tabelas no arquivo database/migrations/2026_10_27_000001_create_gallery_tables.php. O usuário administrativo não é o eleitor. A tabela users já vem na base; acrescentamos is_admin. Todos os registros têm id e timestamps.

### 6. Uma exposição tem muitas obras.

Desenhe as duas relações para votes. A chave estrangeira artwork_id garante que a obra existe, mas sozinha não garante que ela pertence ao exhibition_id informado. Essa consistência é verificada pelo relacionamento no controller.

**Atividade:** Desenhem as quatro tabelas e marquem PK (chave primária) e FK (chave estrangeira).

### 7. O banco também protege as regras.

Trecho de migration, não arquivo completo. Explique que o mesmo eleitor pode votar em outra exposição porque o par é diferente. cascadeOnDelete remove registros dependentes: esta aplicação não expõe uma interface para excluir exposições; discutir essa consequência antes de adicionar exclusão. Não basta desabilitar o botão de votar.

### 8. Prepare o laboratório.

Verifique o laboratório antes da aula. O material acompanha a versão deste repositório, não recomenda iniciar produção com Laravel 10. Não atualize versões no meio da explicação sem revisar dependências. npm não é necessário aqui: CSS e JavaScript estão em public/.

### 9. Escolha um dos dois caminhos.

Os comandos de cópia usam PowerShell. O caminho A usa os arquivos fornecidos; B exige implementar os arquivos nas etapas seguintes. Não execute create-project dentro da aplicação já criada. Se uma instalação antiga for bloqueada pela política de segurança do Composer, não desative a proteção: use a base preparada pelo professor ou planeje a atualização do laboratório. Os slides contêm trechos; os arquivos do projeto são a implementação completa.

### 10. Crie o banco. Depois conecte o Laravel.

Todos os valores são exemplos, sem credenciais do projeto. O professor deve fornecer um usuário de laboratório com permissões no banco. CREATE DATABASE não cria o usuário. Não use root em uma implantação real e não compartilhe o .env.

**Atividade:** Criem o banco e confirmem o nome no cliente MySQL.

### 11. Confirme que a aplicação consegue iniciar.

A chave também participa do HMAC dos votos. O comando migrate executa as migrations existentes: no caminho B, as tabelas da galeria só surgirão depois que suas migrations forem escritas. storage:link cria o acesso público às imagens. Abra o endereço impresso pelo servidor. Não execute migrate:fresh no banco de uma votação real.

### 12. Descreva a estrutura como código.

Somente no caminho B: esses comandos criam os models e migrations, não as regras prontas. Respeite a ordem exhibitions, artworks, votes. No projeto existente as três tabelas estão reunidas em uma migration; não gere duplicatas. Inclua use IlluminateDatabaseSchemaBlueprint e use IlluminateSupportFacadesSchema nas migrations.

### 13. Obras e votos: ligue as tabelas.

Envolva cada bloco em Schema::create no método up(). O down() remove votes, artworks e exhibitions nessa ordem. Artista é opcional no formulário: o controller grava string vazia quando ausente para manter compatibilidade com a coluna existente. Acrescente is_admin boolean default false em users. Consulte a migration completa do repositório.

**Atividade:** Executem migrate e inspecionem as FKs e o índice único no MySQL.

### 14. Cada parte tem uma responsabilidade.

Use a página da exposição como exemplo concreto. CSS e JS não consultam o banco diretamente. O servidor produz a resposta HTML; o navegador apresenta e interage.

### 15. Ensine ao model os relacionamentos.

Os trechos pertencem a classes que estendem IlluminateDatabaseEloquentModel, no namespace AppModels. Artwork também tem votes() hasMany e fillable para title, artist, description, image_path. Vote permite exhibition_id, artwork_id e voter_hash. Consulte os três models completos.

### 16. Uma URL aponta para uma ação.

Arquivo: routes/web.php. Gere o controller com php artisan make:controller GalleryController no caminho B. Explique o binding: Laravel busca a exposição pelo id e responde 404 se não existe. Os limites de requisição precisam ser avaliados na rede escolar, onde muitos dispositivos podem compartilhar IP.

### 17. Do banco para a galeria.

Importe AppModelsExhibition no controller. A view fica em resources/views/gallery/show.blade.php. As chaves duplas escapam HTML; não substitua por saída sem escape para texto enviado por usuários. O layout, mensagens e paginação estão nos arquivos completos.

**Atividade:** Mostrem duas obras na tela e expliquem de onde vem cada variável.

### 18. Quem pode cadastrar as obras?

Leia AuthController e EnsureAdmin. O login usa Auth::attempt com is_admin => true e regenera a sessão. A senha é armazenada com hash. O logout invalida a sessão. O campo is_admin não está em fillable de User, evitando promovê-lo a partir de dados arbitrários do formulário. Esconder links não protege endpoints.

### 19. Crie o administrador e a exposição.

No caminho B, implemente app/Console/Commands/CreateAdmin.php e os controllers a partir da referência antes de executar o comando. Use a interface em /login. Não exponha senhas durante a projeção. O cadastro da exposição já recebe draft por padrão do banco.

**Atividade:** Criem uma exposição e confirmem que ainda não aparece na galeria pública.

### 20. O formulário envia um arquivo.

Trecho didático: adicione labels acessíveis como no formulário real. accept melhora o seletor, mas não protege o servidor. O artista não tem required. O campo de descrição é opcional.

### 21. Valide antes de armazenar.

O controller completo também valida descrição, abre transação, bloqueia a exposição, verifica draft e cria a obra. Se a gravação falhar, remove o arquivo. Arquivos não participam da transação do banco, por isso existe essa limpeza. Ajuste upload_max_filesize e post_max_size no PHP para suportar o limite da aplicação.

**Atividade:** Cadastrem uma obra sem artista; depois tentem enviar um arquivo que não é imagem.

### 22. Valide a escolha e identifique a repetição.

O endereço original não é persistido na tabela votes; o identificador HMAC permite detectar repetição. Isso não é verificação de identidade e não torna os registros automaticamente anônimos. O formulário completo fica em gallery/show.blade.php; gallery.js abre o diálogo e preenche o id da obra.

### 23. E se chegarem dois votos juntos?

Abra o método vote completo em GalleryController. lockForUpdate atua no MySQL/InnoDB dentro da transação. O índice único reforça a regra. Bloquear a exposição serializa os votos dessa exposição; é uma decisão simples para este projeto, com custo de concorrência. Não prometa testes de concorrência com SQLite.

### 24. O voto pertence à obra daquela exposição.

Este slide é um trecho, não substitui o método completo. Os imports estão em GalleryController. O projeto retorna uma mensagem de sucesso e bloqueia o mesmo identificador na próxima tentativa.

**Atividade:** Votem, repitam o e-mail e tentem votar depois de encerrar. Expliquem cada resposta.

### 25. Conte votos, inclusive os zeros.

O percentual é calculado para cada obra ao renderizar; o trecho assume uma obra da coleção. withCount mantém obras sem votos. O id só ordena a exibição entre empatados; não desempata. Leia results() em AdminController. O estado fechado torna o relatório final; antes disso é parcial.

### 26. Compare a apuração com uma consulta SQL.

Troque 1 pelo id da exposição da turma. Essa consulta é para demonstração no cliente MySQL; na aplicação usamos Eloquent. Não use COUNT(*) neste exemplo porque a obra sem voto teria uma linha na junção.

**Atividade:** Comparem os números da consulta com os resultados exibidos no painel.

### 27. Transforme uma view em relatório.

No repositório a dependência já está instalada: use composer install para respeitar o lock. Esse trecho pressupõe uma coleção já classificada com rank; o método completo pdf() obtém um snapshot sob o mesmo bloqueio. A view é reports/results.blade.php e usa DejaVu Sans para acentos. O PDF tem rota protegida.

**Atividade:** Baixem um relatório parcial e um final. Confiram acentos, totais e empate.

### 28. Teste regras, não apenas telas.

A suíte existente tem 10 testes e usa SQLite em memória por phpunit.xml. Leia tests/Feature/GalleryTest.php. O teste de PDF verifica conteúdo e assinatura %PDF-. Antes do evento, faça testes manuais com MySQL e com a rede/computadores reais.

### 29. Quando não funcionar, leia a mensagem.

Altere configurações com cuidado e rode config:clear após ajustes no .env. Procure detalhes em storage/logs/laravel.log. Não projete credenciais, cookies ou o APP_KEY. Um 403 significa usuário autenticado sem permissão, não senha errada.

### 30. Desafio: votar com um cartão.

Preserve o bloqueio da exposição e bloqueie o token antes de verificar used_at. Registre o hash do código, não sua versão legível. O código deve pertencer à exposição. Se gerar QR de link, abrir o link não consome o voto; o POST de confirmação faz isso. Pense em reimpressão, perda, vazamento do código e distribuição um por aluno. Evite registrar tokens em logs e URLs compartilhadas.

### 31. Demonstre o ciclo completo.

Rubrica sugerida: modelagem 2 pontos, galeria/upload 2, votação e integridade 3, apuração/PDF 2, explicação e testes 1. Avalie compreensão e comportamento, não quantidade de linhas. Antes de publicar: atualizar PHP/Laravel, HTTPS, APP_DEBUG=false, backups e ensaio na rede escolar.

### 32. Continue pelo projeto.

Encerre retomando o percurso requisição → validação → banco → resposta. As referências consultadas são oficiais e correspondem à base didática do projeto. O roteiro do professor acompanha os números dos slides.

## Critérios de avaliação (10 pontos)

- Modelagem e integridade: 2.
- Galeria e upload: 2.
- Votação e bloqueios: 3.
- Apuração e PDF: 2.
- Explicação e testes: 1.

## Mapa de arquivos completos

- database/migrations/2026_10_27_000001_create_gallery_tables.php
- app/Models/Exhibition.php, Artwork.php, Vote.php
- app/Http/Controllers/GalleryController.php, AdminController.php, AuthController.php
- app/Http/Middleware/EnsureAdmin.php
- app/Console/Commands/CreateAdmin.php
- routes/web.php
- resources/views/layouts/app.blade.php
- resources/views/gallery/index.blade.php e show.blade.php
- resources/views/admin/index.blade.php e show.blade.php
- resources/views/auth/login.blade.php
- resources/views/reports/results.blade.php
- public/css/gallery.css e public/js/gallery.js
- tests/Feature/GalleryTest.php e phpunit.xml

## Referências

- [Migrations](https://laravel.com/docs/10.x/migrations)
- [Relacionamentos Eloquent](https://laravel.com/docs/10.x/eloquent-relationships)
- [Laravel Dompdf](https://github.com/barryvdh/laravel-dompdf)
- [Referência visual: Fundação Bradesco](https://fundacao.bradesco/)

Para editar o material, altere gerar.cjs e execute node docs/apresentacao/gerar.cjs na raiz do projeto. A geração não altera a aplicação nem consulta o banco.
