<?php

namespace PHPSTORM_META {

	registerArgumentsSet(
		'domain',
		'woocommerce-export-products-csv'
	);

	expectedArguments( \esc_html__(), 1, argumentsSet( 'domain' ) );
	expectedArguments( \__(), 1, argumentsSet( 'domain' ) );
	expectedArguments( \esc_attr__(), 1, argumentsSet( 'domain' ) );

}
