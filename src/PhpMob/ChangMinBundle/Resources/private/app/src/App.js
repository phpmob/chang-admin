import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import SplitPane from 'react-split-pane';
import $ from 'jquery';

import { actions } from './redux/reducer'
import ChangMin from './ChangMin'
import Sidebar from './component/Sidebar'
import Toolbar from './component/Toolbar'
import Menubar from './component/Menubar'
import Breadcrumb from './component/Breadcrumb'

class App extends Component {
    static propTypes = {
        state: PropTypes.object,
        action: PropTypes.object,
    };

    constructor(props) {
        super(props);

        window['ChangMin'] = this.api = new ChangMin(this);
        window['_external_scripts'] = {};
    }

    componentDidMount() {
        setTimeout(() => {
            const nonce = `_${ChangMin.makeid()}`;

            // todo: componet context when support render as component type.
            window[nonce] = this;

            $('script', this.refs.body).each((i, tag) => {
                if (tag.src && !window['_external_scripts'][tag.src]) {
                    $('<script src="' + tag.src + '"></script>').appendTo('body');
                    window['_external_scripts'][tag.src] = true;
                } else {
                    $('<script>(function(context){' + $(tag).text() + '})(' + nonce + ');</script>').appendTo('body');
                }
            });
        }, 300)
    }

    render() {
        const content = this.props.state.get('content');
        const sidebar = this.props.state.get('sidebar');
        const toolbar = this.props.state.get('toolbar');
        const menubar = this.props.state.get('menubar');
        const flash = this.props.state.get('flash');
        const breadcrumb = this.props.state.get('breadcrumb');

        return (
            <div className="window">
                <div className="window-content">
                    <SplitPane className="pane-group" split="vertical" minSize={50} defaultSize={220}>
                        <div className="pane pane-sidebar">
                            <Sidebar data={sidebar}/>
                        </div>
                        <div className="pane pane-body" ref="body">
                            <Menubar data={menubar}/>
                            <Breadcrumb data={breadcrumb}/>
                            { flash && <div className="pane-flash" dangerouslySetInnerHTML={{ __html: flash }}/>}
                            <div className="pane-content">
                                <Toolbar data={toolbar} type="header"/>
                                <div className="pane-data" dangerouslySetInnerHTML={{ __html: content }}/>
                                <Toolbar data={toolbar} type="footer"/>
                            </div>
                        </div>
                    </SplitPane>
                </div>
            </div>
        );
    }
}

function mapStateToProps(state) {
    return {
        state
    };
}

function mapDispatchToProps(dispatch) {
    return {
        action: bindActionCreators({ ...actions }, dispatch)
    };
}

export default connect(
    mapStateToProps,
    mapDispatchToProps
)(App);
