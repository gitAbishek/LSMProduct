import { Icon } from '@chakra-ui/react';
import React from 'react';
import { IoMdStar, IoMdStarOutline } from 'react-icons/io';

interface RatingButton {
	onClick: () => void;
	fill: boolean;
}

const RatingButton: React.FC<RatingButton> = ({ onClick, fill }) =>
	fill ? (
		<Icon as={IoMdStar} w={8} h={8} onClick={onClick} />
	) : (
		<Icon as={IoMdStarOutline} w={8} h={8} onClick={onClick} />
	);

export default RatingButton;
