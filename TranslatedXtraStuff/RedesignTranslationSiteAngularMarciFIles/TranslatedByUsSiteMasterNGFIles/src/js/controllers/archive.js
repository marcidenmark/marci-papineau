( function( $ ) {

	angular.module( 'translatedbyus' )

		.controller( 'ArchiveController', [ '$scope', function( $scope ) {

			$(document).ready(function () {

				c3.generate({
					bindto: '#chart_pairs',
					data: {
						columns: [
							['Dansk > Engelsk', 188444],
							['Engelsk > Dansk', 130016],
							['Engelsk > Svensk', 105636],
							['Engelsk > Svensk', 105636],
							['Dansk > Svensk', 104499],
							['Dansk > Finsk', 122164],
							['Dansk > Norsk', 81319],
							['Dansk > Hollandsk', 62551],
							['Dansk > Fransk', 48876],
							['Dansk > Tysk', 77726],
							['Other', 635397]
						],
						type: 'pie'
					}
				});

				c3.generate({
					bindto: '#chart_world',
					data: {
						columns: [
							['Europa', 70],
							['Asien', 14],
							['Ameria, syd & nord', 12],
							['Afrika', 4]
						],
						type: 'bar'
					}
				});

				c3.generate({
					bindto: '#chart_types',
					data: {
						columns: [
							['Produkttekster', 18],
							['Emballage', 17],
							['Marketing', 17],
							['Tekniske manualer', 13],
							['Juridiske tekster', 13],
							['Faglige tekster', 8],
							['Præsentationer', 7],
							['Diverse', 7]
						],
						type: 'donut'
					}
				});

				c3.generate({
					bindto: '#chart_quality',
					data: {
						columns: [
							['Maskinoversættelse', 11],
							['Basis', 22],
							['Business', 67]
						],
						type: 'donut'
					}
				});

				c3.generate({
					bindto: '#chart_formats',
					data: {
						columns: [
							['MS Word', 24],
							['PDF', 16],
							['MS Excel', 14],
							['MS Powerpoint', 13],
							['HTML', 11],
							['InDesign', 7],
							['XML', 6],
							['Andre', 9]
						],
						type: 'bar'
					}
				});

			});

		} ] );

} )( jQuery );
