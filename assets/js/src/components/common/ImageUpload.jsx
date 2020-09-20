import { React, useState, memo, useCallback } from '@wordpress/element';
import { useDropzone } from 'react-dropzone';
import styled from 'styled-components';
import PropTypes from 'prop-types';
import colors from '../../config/colors';
import { BaseLine } from '../../config/defaultStyle';
import Flex from './Flex';
import Icon from './Icon';
import { BiPlus } from 'react-icons/bi';
import FlexRow from './FlexRow';
import fontSize from '../../config/fontSize';

const ImageUpload = (props) => {
	const { style, className, title, multiple } = props;
	const [file, setFiles] = useState();
	const onDrop = useCallback((acceptedFiles) => {
		setFiles(
			Object.assign(acceptedFiles[0], {
				image: URL.createObjectURL(acceptedFiles[0]),
			})
		);
	});

	console.log(file);

	const {
		getRootProps,
		getInputProps,
		isDragActive,
		isDragAccept,
		isDragReject,
	} = useDropzone({ onDrop, accept: 'image/*' });

	return (
		<Container style={style} className={className}>
			<InputContainer
				{...getRootProps({ isDragActive, isDragAccept, isDragReject })}
				backgroundImage={file?.image}>
				<input {...getInputProps()} multiple={multiple || false} />
				<ContentContainer>
					<FlexRow justify="center">
						<span>
							<Icon icon={<BiPlus />}></Icon>
						</span>
						<span>{title}</span>
					</FlexRow>
				</ContentContainer>
			</InputContainer>
		</Container>
	);
};

ImageUpload.propTypes = {
	style: PropTypes.object,
	className: PropTypes.string,
	title: PropTypes.any,
	multiple: PropTypes.bool,
};

const Container = styled.div``;

const InputContainer = styled(Flex)`
	width: 100%;
	cursor: pointer;
	align-items: center;
	border: 1px dashed ${colors.PLACEHOLDER};
	background-position: 50%;
	background-size: cover;
	background-color: ${colors.GRAY};
	background-image: ${(props) => `url("${props.backgroundImage}")`};
`;

const ContentContainer = styled(Flex)`
	position: relative;
	background-color: ${(props) => {
		if (props.isDragAccept) {
			return colors.PRIMARY;
		}
		if (props.isDragReject) {
			return colors.ALERT;
		}
		return 'transparent';
	}};
	padding: ${BaseLine * 3}px ${BaseLine * 4}px;
	width: 100%;
	color: ${colors.PRIMARY};
	font-weight: 500;
	height: ${BaseLine * 15}px;
	justify-content: center;

	i {
		font-size: ${fontSize.HUGE};
		margin-right: ${BaseLine * 0.5}px;
	}
`;

export default memo(ImageUpload);
