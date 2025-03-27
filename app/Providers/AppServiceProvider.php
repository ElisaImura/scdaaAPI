<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->app->bind('auth.password.broker', function ($app) {
            return new class($app) extends PasswordBrokerManager {
                protected function createTokenRepository(array $config)
                {
                    $key = $this->app['config']['app.key'];

                    return new \Illuminate\Auth\Passwords\DatabaseTokenRepository(
                        $this->app['db']->connection(),
                        $config['table'],
                        $key,
                        $config['expire'],
                        $config['throttle']
                    );
                }

                protected function createUserProvider($name = null)
                {
                    return new class($this->app['hash'], $this->app['config']['auth.providers.users.model']) implements \Illuminate\Contracts\Auth\UserProvider {
                        protected $hasher;
                        protected $model;

                        public function __construct($hasher, $model)
                        {
                            $this->hasher = $hasher;
                            $this->model = $model;
                        }

                        public function retrieveById($identifier)
                        {
                            return $this->createModel()->newQuery()->find($identifier);
                        }

                        public function retrieveByToken($identifier, $token)
                        {
                            $model = $this->createModel();

                            return $model->newQuery()
                                ->where($model->getKeyName(), $identifier)
                                ->where($model->getRememberTokenName(), $token)
                                ->first();
                        }

                        public function updateRememberToken($user, $token)
                        {
                            $user->setRememberToken($token);
                            $user->save();
                        }

                        public function retrieveByCredentials(array $credentials)
                        {
                            $query = $this->createModel()->newQuery();

                            if (isset($credentials['uss_email'])) {
                                $query->where('uss_email', $credentials['uss_email']);
                            }

                            return $query->first();
                        }

                        public function validateCredentials($user, array $credentials)
                        {
                            return $this->hasher->check(
                                $credentials['uss_clave'], $user->uss_clave
                            );
                        }

                        protected function createModel()
                        {
                            $class = '\\' . ltrim($this->model, '\\');

                            return new $class;
                        }
                    };
                }
            };
        });
    }
}
