<?php
namespace Prometheus;
class TextWidget extends Component {
	public function render() {
		$text = isset( $this->props->text ) ? $this->props->text : 'This is a text widget with no data!';
		$className = isset( $this->props->className ) ? $this->props->className : '';
		return React::createElement('div',['className' => $className],$text);
	}
}

