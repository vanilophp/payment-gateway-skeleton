# Vanilo Payment Gateway Skeleton

This repo contains a boilerplate payment gateway skeleton to help you to
create a new gateway integration.

## Usage

1. Download this repo to a folder on your computer
2. Extract the zip and change to that folder
3. Run `php init.php`
4. Answer the questions
5. Use the generated code

## Variables

| Name                        | Example            | Meaning                                                                                                          |
|:----------------------------|:-------------------|:-----------------------------------------------------------------------------------------------------------------|
| `{:author_name:}`           | John Smith         | Your name. Used in LICENSE, composer.json                                                                        |
| `{:payment_gateway_name:}`  | Stripe             | The name of the payment gateway you're implementing support for                                                  |
| `{:composer_vendor:}`       | acme               | The composer vendor name your package will be using. used in composer.json, README.md                            |
| `{:composer_package_name:}` | vanilo-stripe      | The composer package name of your package. used in composer.json, README.md                                      |
| `{:name_space_root:}`       | `Acme\Stripe`      | The root PHP namespace of your package                                                                           |
| `{:github_repo_path:}`      | acme/vanilo-stripe | The path of the repo at Github. If it's hosted somewhere else, change later manually in the README               |
| `{:style_ci_id:}`           | 348627499          | The StyleCI id connected to your repo. If you're not using StyleCI just enter anything and remove it from README |
