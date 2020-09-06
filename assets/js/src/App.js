import { Component } from '@wordpress/element';

import { __ } from '@wordpress/i18n';

import { HashRouter as Router, Switch, Route } from 'react-router-dom';

import * as screens from './screens';
import { Link } from 'react-router-dom';

export default class App extends Component {
  render() {
    return (
      <Router>
        <div className="masteriyo">
          <Switch>
            <Route path="/dashboard">
              <screens.Dashboard />
            </Route>
          </Switch>
          <nav>
            <header className="masteriyo-header">
              {__('Masteriyo Live Reload Test', 'masteriyo')}
            </header>
            <Link to="/dashboard">Go to dashboard</Link>
          </nav>
        </div>
      </Router>
    );
  }
}
