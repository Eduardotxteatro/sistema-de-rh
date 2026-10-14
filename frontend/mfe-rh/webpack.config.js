const { shareAll, withModuleFederationPlugin } = require('@angular-architects/module-federation/webpack');

const config = withModuleFederationPlugin({
  name: 'mfe-rh',
  exposes: {
    './Component': './src/app/app.component.ts',
    './EmployeesModule': './src/app/employees/employees.module.ts',
  },
  shared: {
    ...shareAll({ singleton: true, strictVersion: true, requiredVersion: 'auto' }),
  },
});

// Garante que o Webpack não use sintaxe de módulo ES no loader de scripts
config.output.scriptType = 'text/javascript';

// Configuração de Proxy para encaminhar chamadas de API ao CakePHP
config.devServer = {
  ...config.devServer,
  port: 4201,
  proxy: [
    {
      context: ['/api'],
      target: 'http://localhost:8765',
      secure: false,
      changeOrigin: true,
      logLevel: 'debug'
    }
  ]
};

module.exports = config;