import {
	Button,
	ButtonGroup,
	Collapse,
	Container,
	HStack,
	Icon,
	Link,
	Stack,
	Text,
} from '@chakra-ui/react';
import http from '@wordpress/api-fetch';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { BiHeart, BiX } from 'react-icons/bi';
import { FullStar } from '../../constants/images';
import localized from '../../utils/global';

const ReviewNotice: React.FC = () => {
	const [isShowNotice, setIsShowNotice] = useState(
		localized.show_review_notice === 'yes'
	);

	const handleNotice = (masteriyo_action: string) => {
		setIsShowNotice(false);

		localized.show_review_notice = 'no';

		const formData = new FormData();

		formData.append('action', 'masteriyo_review_notice');
		formData.append('nonce', localized.review_notice_nonce);
		formData.append('masteriyo_action', masteriyo_action);

		http({
			url: localized.ajax_url,
			method: 'post',
			body: formData,
		});
	};

	return (
		<Collapse in={isShowNotice} style={{ width: '100%' }}>
			<Container maxW="container.xl">
				<Stack background="white" p="6" spacing="4" shadow="box">
					<HStack align="flex-start" spacing="4">
						<Icon width="12" height="12" fill="red" as={BiHeart} />
						<Stack flex="1">
							<Text fontSize="16px" fontWeight="bold">
								{__('Love using LMS by Masteriyo?', 'masteriyo')}
							</Text>
							<HStack
								fontSize="14px"
								display="flex"
								alignItems="center"
								color="#7C7D8F">
								<Text>Please do us a favor by providing 5-star</Text>
								<HStack mx="2">
									<Icon width="4" height="4" fill="#fec30d" as={FullStar} />
									<Icon width="4" height="4" fill="#fec30d" as={FullStar} />
									<Icon width="4" height="4" fill="#fec30d" as={FullStar} />
									<Icon width="4" height="4" fill="#fec30d" as={FullStar} />
									<Icon width="4" height="4" fill="#fec30d" as={FullStar} />
								</HStack>
								<Text>rating at WordPress.org. Let us know</Text>
								<Link
									href="https://masteriyo.com/contact/"
									target="_blank"
									mx="1"
									textDecoration="underline">
									here
								</Link>
								<Text>if you have any query. - Masteriyo Team</Text>
							</HStack>
						</Stack>
						<Icon
							width="6"
							height="6"
							fill="#ACACBE"
							cursor="pointer"
							as={BiX}
							onClick={() => handleNotice('close_notice')}
						/>
					</HStack>
					<ButtonGroup spacing="6">
						<Link
							href="https://wordpress.org/support/plugin/learning-management-system/reviews/?rate=5#new-post"
							target="_blank">
							<Button
								size="sm"
								colorScheme="primary"
								variant="solid"
								onClick={() => handleNotice('review_received')}>
								{__("Sure, I'd love to", 'masteriyo')}
							</Button>
						</Link>
						<Button
							size="sm"
							variant="unstyled"
							onClick={() => handleNotice('remind_me_later')}
							color="#7C7D8F">
							{__('Maybe later', 'masteriyo')}
						</Button>
						<Button
							size="sm"
							variant="unstyled"
							onClick={() => handleNotice('already_reviewed')}
							color="#7C7D8F">
							{__('I already did', 'masteriyo')}
						</Button>
					</ButtonGroup>
				</Stack>
			</Container>
		</Collapse>
	);
};

export default ReviewNotice;
