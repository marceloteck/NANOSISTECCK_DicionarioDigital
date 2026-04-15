<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    public function adminpainel(): Response
    {
        $adminMenu = $this->buildAdminMenu();

        return Inertia::render('Pages/admin/index', [
            'adminMenu' => $adminMenu,
            'adminStats' => [
                'total_admin_links' => collect($adminMenu)
                    ->where('key', 'admin')
                    ->pluck('links')
                    ->flatten(1)
                    ->count(),
                'total_public_links' => collect($adminMenu)
                    ->where('key', 'public')
                    ->pluck('links')
                    ->flatten(1)
                    ->count(),
                'total_quick_actions' => collect($adminMenu)
                    ->pluck('links')
                    ->flatten(1)
                    ->count(),
            ],
        ]);
    }

    protected function buildAdminMenu(): array
    {
        $menu = [];

        $adminLinks = [
            [
                'label' => 'Painel principal',
                'url' => route('index.admin'),
                'description' => 'Visão geral do ambiente administrativo.',
            ],
            [
                'label' => 'Listar posts',
                'url' => route('admin.posts.index'),
                'description' => 'Acompanhe, revise e organize todas as postagens.',
            ],
            [
                'label' => 'Criar novo post',
                'url' => route('admin.posts.create'),
                'description' => 'Abra rapidamente a tela de criação de conteúdo.',
            ],
        ];

        $publicLinks = [
            [
                'label' => 'Página inicial',
                'url' => route('index.home'),
                'description' => 'Acesse a home pública do projeto.',
            ],
            [
                'label' => 'Posts públicos',
                'url' => route('posts.index'),
                'description' => 'Veja como a listagem de posts aparece para o usuário.',
            ],
            [
                'label' => 'Busca',
                'url' => route('search.index'),
                'description' => 'Abra a página de busca pública do site.',
            ],
            [
                'label' => 'Perfil',
                'url' => route('profile.edit'),
                'description' => 'Atualize dados da conta autenticada.',
            ],
            [
                'label' => 'Robots.txt',
                'url' => route('seo.robots'),
                'description' => 'Verifique rapidamente as regras de rastreamento.',
            ],
            [
                'label' => 'Sitemap XML',
                'url' => route('seo.sitemap'),
                'description' => 'Confira o mapa do site utilizado por buscadores.',
            ],
        ];

        if (config('project.modules.tools', false)) {
            $publicLinks[] = [
                'label' => 'Ferramentas',
                'url' => route('tools.index'),
                'description' => 'Abra a área pública de ferramentas do projeto.',
            ];
        }

        if (config('project.modules.institutional_pages', true)) {
            $publicLinks[] = [
                'label' => 'Páginas institucionais',
                'url' => url('/institucional/sobre'),
                'description' => 'Acesso rápido para validar páginas institucionais.',
            ];
        }

        if (config('project.modules.taxonomy', true)) {
            $publicLinks[] = [
                'label' => 'Categorias',
                'url' => url('/categoria/exemplo'),
                'description' => 'Atalho base para estrutura de categorias.',
            ];

            $publicLinks[] = [
                'label' => 'Tags',
                'url' => url('/tag/exemplo'),
                'description' => 'Atalho base para estrutura de tags.',
            ];
        }

        $systemLinks = [
            [
                'label' => 'Login',
                'url' => route('login'),
                'description' => 'Tela pública de autenticação do sistema.',
            ],
            [
                'label' => 'Registro',
                'url' => route('register'),
                'description' => 'Cadastro público de usuário, quando habilitado.',
            ],
            [
                'label' => 'Recuperar senha',
                'url' => route('password.request'),
                'description' => 'Fluxo de recuperação de senha do sistema.',
            ],
            [
                'label' => 'Verificação de e-mail',
                'url' => route('verification.notice'),
                'description' => 'Tela de aviso de verificação para usuários autenticados.',
            ],
            [
                'label' => 'Confirmar senha',
                'url' => route('password.confirm'),
                'description' => 'Tela de confirmação extra de senha.',
            ],
        ];

        $menu[] = [
            'key' => 'admin',
            'eyebrow' => 'Administração',
            'title' => 'Gestão principal',
            'description' => 'Área central para controlar conteúdo, revisar o painel e administrar as postagens do sistema.',
            'links' => $adminLinks,
        ];

        $menu[] = [
            'key' => 'public',
            'eyebrow' => 'Site público',
            'title' => 'Links estratégicos do projeto',
            'description' => 'Atalhos para validar páginas públicas, SEO técnico e navegação principal do usuário final.',
            'links' => $publicLinks,
        ];

        $menu[] = [
            'key' => 'system',
            'eyebrow' => 'Conta e autenticação',
            'title' => 'Fluxos do sistema',
            'description' => 'Acesse rapidamente os fluxos principais de autenticação, perfil e segurança da conta.',
            'links' => $systemLinks,
        ];

        return $menu;
    }
}