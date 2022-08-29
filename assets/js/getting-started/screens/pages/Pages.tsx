import {
	Box,
	Button,
	ButtonGroup,
	Flex,
	FormControl,
	FormLabel,
	Icon,
	Link,
	Select,
	Skeleton,
	Stack,
	Tooltip,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext } from 'react-hook-form';
import { BiInfoCircle } from 'react-icons/bi';
import { useQuery } from 'react-query';
import API from '../../../back-end//utils/api';
import { infoIconStyles } from '../../../back-end/config/styles';
import urls from '../../../back-end/constants/urls';
import { SetttingsMap } from '../../../back-end/types';
import PagesAPI from '../../../back-end/utils/pages';
interface Props {
	dashboardURL: string;
	prevStep: () => void;
	nextStep: () => void;
}

const Pages: React.FC<Props> = (props) => {
	const { dashboardURL, prevStep, nextStep } = props;
	const { register } = useFormContext();
	const settingsApi = new API(urls.settings);
	const pageAPI = new PagesAPI();
	const pagesQuery = useQuery('pages', () => pageAPI.list());
	const settingsQuery = useQuery<SetttingsMap>('settings', () =>
		settingsApi.list()
	);

	const renderPagesOption = () => {
		try {
			return (
				<>
					<option value="">{__('Select a page.', 'masteriyo')}</option>
					{pagesQuery?.data?.data?.map(
						(page: { id: number; title: string }) => (
							<option value={page.id} key={page.id}>
								{page.title}
							</option>
						)
					)}
				</>
			);
		} catch (error) {
			return;
		}
	};

	return (
		<Box rounded="3px">
			<Box bg="white" p="30" shadow="box">
				<Stack direction="column" spacing="8">
					{pagesQuery.isLoading || settingsQuery.isLoading ? (
						<>
							<Stack spacing="8">
								<Skeleton h="6" />
								<Skeleton h="6" />
								<Skeleton h="6" />
							</Stack>
						</>
					) : (
						<>
							<FormControl>
								<Flex justify="space-between" align="center">
									<FormLabel sx={{ fontWeight: 'bold' }}>
										{__('Course List', 'masteriyo')}
										<Tooltip
											label={__(
												'Select a page to be set as courses page. This page will show all available courses.',
												'masteriyo'
											)}
											hasArrow
											fontSize="xs">
											<Box as="span" sx={infoIconStyles}>
												<Icon as={BiInfoCircle} />
											</Box>
										</Tooltip>
									</FormLabel>
									<Select
										defaultValue={
											settingsQuery?.data?.general?.pages?.courses_page_id
										}
										w="md"
										{...register('general.pages.courses_page_id')}>
										{renderPagesOption()}
									</Select>
								</Flex>
							</FormControl>

							<FormControl>
								<Flex justify="space-between" align="center">
									<FormLabel sx={{ fontWeight: 'bold' }}>
										{__('Learning', 'masteriyo')}
										<Tooltip
											label={__(
												'Select a page to be set as learning page. This page runs the distraction free course learning page for any course.',
												'masteriyo'
											)}
											hasArrow
											fontSize="xs">
											<Box as="span" sx={infoIconStyles}>
												<Icon as={BiInfoCircle} />
											</Box>
										</Tooltip>
									</FormLabel>
									<Select
										defaultValue={
											settingsQuery?.data?.general?.pages?.learn_page_id
										}
										w="md"
										{...register('general.pages.learn_page_id')}>
										{renderPagesOption()}
									</Select>
								</Flex>
							</FormControl>

							<FormControl>
								<Flex justify="space-between" align="center">
									<FormLabel sx={{ fontWeight: 'bold' }}>
										{__('Account', 'masteriyo')}
										<Tooltip
											label={__(
												'Select a page to be set as account page. This page shows the account of both student or instructor. The page should contain shortcode [masteriyo_account].',
												'masteriyo'
											)}
											hasArrow
											fontSize="xs">
											<Box as="span" sx={infoIconStyles}>
												<Icon as={BiInfoCircle} />
											</Box>
										</Tooltip>
									</FormLabel>
									<Select
										defaultValue={
											settingsQuery?.data?.general?.pages?.account_page_id
										}
										w="md"
										{...register('general.pages.account_page_id')}>
										{renderPagesOption()}
									</Select>
								</Flex>
							</FormControl>

							<FormControl>
								<Flex justify="space-between" align="center">
									<FormLabel sx={{ fontWeight: 'bold' }}>
										{__('Checkout', 'masteriyo')}
										<Tooltip
											label={__(
												'Select a page to be set as checkout page. This page shows the checkout page while purchasing any course. The page should contain shortcode [masteriyo_checkout].',
												'masteriyo'
											)}
											hasArrow
											fontSize="xs">
											<Box as="span" sx={infoIconStyles}>
												<Icon as={BiInfoCircle} />
											</Box>
										</Tooltip>
									</FormLabel>
									<Select
										defaultValue={
											settingsQuery?.data?.general?.pages?.checkout_page_id
										}
										w="md"
										{...register('general.pages.checkout_page_id')}>
										{renderPagesOption()}
									</Select>
								</Flex>
							</FormControl>

							<Flex justify="space-between" align="center">
								<Button onClick={prevStep} rounded="3px" variant="outline">
									{__('Back', 'masteriyo')}
								</Button>
								<ButtonGroup>
									<Link href={dashboardURL ? dashboardURL : '#'}>
										<Button variant="ghost">
											{__('Skip to Dashboard', 'masteriyo')}
										</Button>
									</Link>
									<Button onClick={nextStep} rounded="3px">
										{__('Next', 'masteriyo')}
									</Button>
								</ButtonGroup>
							</Flex>
						</>
					)}
				</Stack>
			</Box>
		</Box>
	);
};

export default Pages;
