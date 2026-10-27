# Galeria de Arte · Fundação Bradesco

Galeria de arte e votação em Laravel + MySQL, com interface em português, painel de curadoria e relatório PDF.

## Funcionalidades

- Exposições independentes com estados: preparação, votação aberta e encerrada.
- Upload de obras com título, artista, descrição e imagem JPG, PNG ou WebP (até 8 MB e 12000 × 12000 pixels).
- Galeria responsiva, paginação e ampliação das imagens.
- Um voto por e-mail normalizado em cada exposição. O voto não pode ser alterado.
- E-mail armazenado somente como HMAC; endereço original não é gravado no banco.
- Apuração administrativa com votos, percentuais e posições empatadas.
- PDF real para download com resultado parcial/final, data, total e classificação.
- Login administrativo, proteção CSRF, limite de tentativas e validação dos arquivos.
- A abertura exige ao menos uma obra. Após abrir, não é permitido adicionar obras. O encerramento é definitivo.

## Executar localmente

O projeto mantém a base Laravel 10 e requer PHP 8.1+, Composer e MySQL. Extensões: pdo_mysql, mbstring, fileinfo, dom e gd. Os testes usam pdo_sqlite. O frontend usa CSS e JavaScript locais e não depende de npm ou Vite.

1. Instale as dependências com `composer install`.
2. Se ainda não houver `.env`, copie `.env.example`. Configure seu banco e usuário existentes:

```dotenv
APP_NAME="Galeria de Arte - Fundação Bradesco"
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=votacao
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

Crie o banco `votacao` no MySQL se necessário, com charset `utf8mb4`. Não coloque senhas no repositório.

3. Somente se `APP_KEY` estiver vazia, execute `php artisan key:generate`. Preserve essa chave após receber votos: ela é usada para identificar e-mails repetidos.
4. Execute:

```shell
php artisan config:clear
php artisan migrate
php artisan storage:link
php artisan gallery:admin curador@exemplo.com
php artisan serve
```

O comando de administrador solicita nome e senha oculta de pelo menos 12 caracteres. Não existe senha padrão. Um e-mail já cadastrado é rejeitado.

5. Acesse `http://127.0.0.1:8000/login`, crie a exposição, envie as obras e abra a votação. A galeria pública fica em `/`. No painel, encerre a votação quando desejar e baixe o PDF.

Para uploads de 8 MB, ajuste o `php.ini` do servidor: `upload_max_filesize=8M` e `post_max_size=12M` ou maior, e reinicie o PHP/Apache. O diretório público do servidor deve apontar para `public/`.

## Validação

```shell
php artisan test --compact
```

Os testes usam SQLite em memória, nunca o banco configurado no `.env`. Cobrem galeria, restrição de rascunhos, normalização e duplicidade de votos, voto em obra de outra exposição, votação fechada, consentimento, autenticação, autorização, upload, transições, empates e assinatura de arquivo PDF.

## Regras e limites

O e-mail não é verificado por mensagem. A regra garante um voto por endereço informado, não um voto por pessoa física. Para uma eleição com exigência de identidade, implemente verificação de e-mail ou convites individuais antes de abrir ao público.

Votos, uploads e mudanças de estado usam transações e bloqueio da exposição; há também índice único por exposição e identificador de eleitor. Isso evita duplicidade concorrente e votos após o encerramento no MySQL/InnoDB. A suíte em SQLite valida os fluxos, mas não exercita concorrência real do MySQL. A apuração pública não é exibida; apenas administradores acessam resultados e PDF. Em caso de empate não há desempate arbitrário.

## Situação do ambiente na implementação

- Dependência PDF instalada: barryvdh/laravel-dompdf, conforme https://github.com/barryvdh/laravel-dompdf.
- Link `public/storage` criado.
- MySQL respondeu, mas recusou as credenciais atuais do `.env` (erro 1045). Nenhuma migração foi executada nesse banco. Ajuste as credenciais antes de usar.
- PHP local: 8.1.25. A instalação apresenta avisos de extensões Oracle/Firebird ausentes e módulos duplicados; corrija as entradas no `php.ini` do XAMPP.
- `composer audit` identificou alertas no Laravel 10 existente. Antes de publicar, atualize PHP e Laravel para versões suportadas e execute novamente a auditoria e os testes. Guia oficial: https://laravel.com/docs/12.x/upgrade.
- Em produção, use HTTPS, `APP_DEBUG=false` e cookies de sessão seguros. Preserve `APP_KEY`, banco e arquivos de `storage/app/public` nos backups.
- Não foi realizada inspeção visual em navegador nem validação de integração com MySQL, devido à indisponibilidade de credenciais válidas.

## Identidade visual

Tema vermelho e branco inspirado no site oficial da Fundação Bradesco (https://fundacao.bradesco/), aplicado à galeria, ao login, ao painel e ao PDF. O logotipo branco foi obtido em https://fundacao.bradesco/assets/images/logo_white.svg e está armazenado localmente em public/images/fundacao-bradesco-white.svg. A paleta adaptada usa vermelho #c8102e e rosa #ed145b; não substitui um manual de marca institucional.
